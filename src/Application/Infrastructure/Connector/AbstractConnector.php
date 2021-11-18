<?php

declare(strict_types=1);

namespace App\Application\Infrastructure\Connector;

class AbstractConnector
{
    public final const TRANSACTION_ID_HEADER = 'X-Transaction-Id';
}