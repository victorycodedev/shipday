<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Auth;

final readonly class BasicAuth implements Authenticator
{
    public function __construct(
        private string $apiKey,
        private ?string $xApiKey = null,
    )
    {
    }

    /**
     * @return array<string, string>
     */
    #[\Override]
    public function headers(): array
    {
        $headers = [
            'Authorization' => sprintf('Basic %s', $this->apiKey),
        ];

        if ($this->xApiKey !== null && $this->xApiKey !== '') {
            $headers['x-api-key'] = $this->xApiKey;
        }

        return $headers;
    }
}
