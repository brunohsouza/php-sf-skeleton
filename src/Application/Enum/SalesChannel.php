<?php

declare(strict_types=1);

namespace App\Application\Enum;

enum SalesChannel {
    case Retail;
    case Online;

    public static function validateSalesChannel(string $salesChannel): bool
    {
        return match ($salesChannel) {
            (self::Retail)->name, (self::Online)->name => true,
            default => false,
        };
    }

    public static function validateSalesChannelByTypeHint(SalesChannel $salesChannel)
    {}
}