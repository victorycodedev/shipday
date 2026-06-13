<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Exceptions;

use Exception;
use Throwable;

class ShipdayException extends Exception
{
    /**
     * @param array<string, array<int, string>> $headers
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        private readonly int $statusCode = 0,
        private readonly array|string|null $response = null,
        private readonly array $headers = [],
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param array<string, array<int, string>> $headers
     */
    public static function fromResponse(int $statusCode, array|string|null $response, array $headers = []): self
    {
        $message = match (true) {
            is_array($response) && isset($response['message']) => (string) $response['message'],
            is_array($response) && isset($response['error']) => (string) $response['error'],
            is_string($response) && $response !== '' => $response,
            default => sprintf('Shipday API request failed with status code %d.', $statusCode),
        };

        return new self(
            message: $message,
            code: $statusCode,
            statusCode: $statusCode,
            response: $response,
            headers: $headers,
        );
    }

    public static function invalidJson(string $message = 'Shipday returned invalid JSON.'): self
    {
        return new self($message);
    }

    public static function invalidWebhookPayload(string $message = 'Invalid Shipday webhook payload.'): self
    {
        return new self($message);
    }

    public static function invalidWebhookToken(): self
    {
        return new self('Invalid Shipday webhook token.', 0, 401);
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function response(): array|string|null
    {
        return $this->response;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function headers(): array
    {
        return $this->headers;
    }

    public function errorId(): ?int
    {
        return is_array($this->response) && isset($this->response['id'])
            ? (int) $this->response['id']
            : null;
    }

    public function errorName(): ?string
    {
        return is_array($this->response) && isset($this->response['error'])
            ? (string) $this->response['error']
            : null;
    }

    public function details(): mixed
    {
        return is_array($this->response) && array_key_exists('details', $this->response)
            ? $this->response['details']
            : null;
    }

    public function retryAfter(): ?string
    {
        return $this->headers['Retry-After'][0] ?? $this->headers['retry-after'][0] ?? null;
    }
}
