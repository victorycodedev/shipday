<?php

namespace Victorycodedev\Shipday\Exceptions;

use Exception;

class TooManyRequestException extends Exception
{
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
