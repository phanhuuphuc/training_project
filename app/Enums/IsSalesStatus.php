<?php

namespace App\Enums;

class IsSalesStatus
{
    const STOP_SELLING = 0;
    const ONE_SALE = 1;

    public static function getAllIsSalesStatus()
    {
        return [
            self::STOP_SELLING,
            self::ONE_SALE
        ];
    }
}
