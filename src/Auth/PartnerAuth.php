<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Auth;

final readonly class PartnerAuth implements Authenticator
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
            'PARTNER-API-KEY' => $this->apiKey,
        ];
    }
}
