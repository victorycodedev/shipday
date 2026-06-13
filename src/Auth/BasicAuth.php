<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Auth;

final readonly class BasicAuth implements Authenticator
{
    public function __construct(private string $apiKey)
    {
    }

    /**
     * @return array<string, string>
     */
    public function headers(): array
    {
        return [
            'Authorization' => sprintf('Basic %s', $this->apiKey),
        ];
    }
}
