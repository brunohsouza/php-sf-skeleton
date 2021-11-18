<?php

declare(strict_types=1);

namespace App\Application\Exception;

use App\Application\Exception\ContextualExceptionInterface;
use App\Application\Exception\LoggableExceptionInterface;

class GatewayException extends \RuntimeException implements LoggableExceptionInterface, ContextualExceptionInterface
{
    public function getLoggableMessage(): string
    {
        return '[{method} {path}] Unable to perform request: "{message}".';
    }

    public function getLevel(): string
    {
        return 'critical';
    }
}
