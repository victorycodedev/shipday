<?php

namespace Victorycodedev\Shipday;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Victorycodedev\Shipday\Exceptions\BadRequestException;
use Victorycodedev\Shipday\Exceptions\TooManyRequestException;
use Victorycodedev\Shipday\Exceptions\UnauthorizedException;

trait MakeRequest
{
    protected function request(string $method, string $uri, array $payload = []): array
    {
        $response = $this->client->request($method, $uri, empty($payload) ? [] : ['json' => $payload]);

        if (!$this->isSuccessful($response)) {
            return $this->handleError($response);
        }

        return json_decode($response->getBody(), true);
    }

    private function isSuccessful(ResponseInterface $response): bool
    {
        if (!$response) {
            return false;
        }

        return $response->getStatusCode() >= 200 && $response->getStatusCode() < 300;
    }

    private function handleError(ResponseInterface $response): void
    {
        match ($response->getStatusCode()) {
            400     => throw new BadRequestException((string) $response->getBody()),
            401     => throw new UnauthorizedException((string) $response->getBody()),
            429     => throw new TooManyRequestException((string) $response->getBody()),
            default => throw new Exception((string) $response->getBody()),
        };
    }
}
