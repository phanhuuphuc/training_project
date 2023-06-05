<?php

namespace App\Common;

use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class LastProduct
{
    const KEY_LAST_PRODUCT_ID = 'last_product_id';

    public static function getLastProductId($numberOfProductIds = 1)
    {
        return DB::transaction(function () use ($numberOfProductIds) {
            $row = Setting::where('key', self::KEY_LAST_PRODUCT_ID)->first();
            $lastProductId = (int)$row->value;
            $row->value = $lastProductId + $numberOfProductIds;
            $row->save();
            return $lastProductId;
        });
    }
}
