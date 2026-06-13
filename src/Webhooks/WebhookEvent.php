<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Webhooks;

use Victorycodedev\Shipday\Enums\WebhookEventType;

abstract readonly class WebhookEvent
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(protected array $payload)
    {
    }

    public function event(): ?string
    {
        return isset($this->payload['event']) ? (string) $this->payload['event'] : null;
    }

    public function eventType(): ?WebhookEventType
    {
        $event = $this->event();

        return $event === null ? null : WebhookEventType::tryFrom($event);
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return $this->payload;
    }

    protected function intValue(string $key): ?int
    {
        return isset($this->payload[$key]) ? (int) $this->payload[$key] : null;
    }

    protected function floatValue(string $key): ?float
    {
        return isset($this->payload[$key]) ? (float) $this->payload[$key] : null;
    }
}
