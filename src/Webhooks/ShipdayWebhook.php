<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Webhooks;

use JsonException;
use Victorycodedev\Shipday\Exceptions\ShipdayException;

final class ShipdayWebhook
{
    /**
     * @param array<string, mixed> $headers
     */
    public static function parse(string $payload, array $headers = [], ?string $token = null): WebhookEvent
    {
        self::validateToken($headers, $token);

        try {
            $decoded = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw ShipdayException::invalidWebhookPayload($exception->getMessage());
        }

        if (! is_array($decoded)) {
            throw ShipdayException::invalidWebhookPayload();
        }

        return match ($decoded['event'] ?? null) {
            'LOCATION_UPDATE' => new DriverLocationUpdated($decoded),
            default => new OrderStatusUpdated($decoded),
        };
    }

    /**
     * @param array<string, mixed> $headers
     */
    public static function fromRequest(string $payload, array $headers = [], ?string $token = null): WebhookEvent
    {
        return self::parse($payload, $headers, $token);
    }

    public static function fromGlobals(?string $token = null): WebhookEvent
    {
        return self::parse(
            payload: (string) file_get_contents('php://input'),
            headers: function_exists('getallheaders') ? getallheaders() : [],
            token: $token,
        );
    }

    /**
     * @param array<string, mixed> $headers
     */
    private static function validateToken(array $headers, ?string $expectedToken): void
    {
        if ($expectedToken === null || $expectedToken === '') {
            return;
        }

        $actualToken = self::header($headers, 'token');

        if (! is_string($actualToken) || ! hash_equals($expectedToken, $actualToken)) {
            throw ShipdayException::invalidWebhookToken();
        }
    }

    /**
     * @param array<string, mixed> $headers
     */
    private static function header(array $headers, string $name): string|array|null
    {
        foreach ($headers as $key => $value) {
            if (strtolower((string) $key) !== strtolower($name)) {
                continue;
            }

            return is_array($value) ? ($value[0] ?? null) : $value;
        }

        return null;
    }
}
