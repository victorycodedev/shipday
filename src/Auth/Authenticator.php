<?php

declare(strict_types=1);

namespace Victorycodedev\Shipday\Auth;

interface Authenticator
{
    /**
     * @return array<string, string>
     */
    public function headers(): array;
}
