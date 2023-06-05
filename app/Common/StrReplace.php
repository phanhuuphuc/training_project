<?php

namespace App\Common;

class StrReplace
{
    public static function escapeBeforeSearch($str)
    {
        return str_replace('_', '\_', str_replace('%', '\%', str_replace('\\', '\\\\', $str)));
    }
}
