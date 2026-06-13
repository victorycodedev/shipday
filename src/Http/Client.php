<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Http;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use JsonException;
use Victorycodedev\Shipday\Auth\Authenticator;
use Victorycodedev\Shipday\Exceptions\ShipdayException;

final readonly class Client
{
    public const string DEFAULT_BASE_URI = 'https://api.shipday.com';

    public function __construct(
        private ClientInterface $client,
        private Authenticator $authenticator,
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<mixed>
     */
    public function get(string $uri, array $query = []): array
    {
        return $this->request('GET', $uri, query: $query);
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<mixed>
     */
    public function post(string $uri, array $payload = []): array
    {
        return $this->request('POST', $uri, payload: $payload);
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<mixed>
     */
    public function put(string $uri, array $payload = []): array
    {
        return $this->request('PUT', $uri, payload: $payload);
    }

    /**
     * @return array<mixed>
     */
    public function delete(string $uri): array
    {
        return $this->request('DELETE', $uri);
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $query
     *
     * @return array<mixed>
     */
    public function request(string $method, string $uri, array $payload = [], array $query = []): array
    {
        $options = [
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::HEADERS => array_merge([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ], $this->authenticator->headers()),
        ];

        if ($payload !== []) {
            $options[RequestOptions::JSON] = $payload;
        }

        if ($query !== []) {
            $options[RequestOptions::QUERY] = $query;
        }

        try {
            $response = $this->client->request($method, $uri, $options);
        } catch (GuzzleException $exception) {
            throw new ShipdayException(
                message: $exception->getMessage(),
                code: (int) $exception->getCode(),
                previous: $exception,
            );
        }

        $body = trim((string) $response->getBody());
        $decoded = $this->decode($body);
        $statusCode = $response->getStatusCode();

        if ($statusCode < 200 || $statusCode >= 300) {
            throw ShipdayException::fromResponse($statusCode, $decoded, $response->getHeaders());
        }

        return is_array($decoded) ? $decoded : [];
    }

    private function decode(string $body): array|string|null
    {
        if ($body === '') {
            return [];
        }

        try {
            return json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return $body;
        }
    }
}
