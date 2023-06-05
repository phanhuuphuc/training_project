<?php

namespace Mi\L5Core\Filters;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Mi\L5Core\Contracts\FilterInterface;

abstract class BaseFilter implements FilterInterface
{
    const DEFAULT_TIMEZONE = 'Asia/Tokyo';

    /**
     * @var \Illuminate\Support\Collection
     */
    protected static $grabInputs;

    /**
     * @var array
     */
    protected static $optionalInputs = [];

    /**
     * Grab other input for using in the filter
     *
     * @param \Illuminate\Support\Collection $data
     * @return \Illuminate\Support\Collection
     */
    public static function grabInputs(Collection $data)
    {
        self::$grabInputs = $data->only(self::$optionalInputs);
    }

    /**
     * Escape input type string for using in the filter ILIKE
     *
     * @param string $string
     *
     * @return string
     */
    public static function escapeString(string $string)
    {
        return addcslashes($string, '%_');
    }

    /**
     * check format string is Y-m-d
     *
     * @param string $string
     * @return boolean
     */
    private static function isDate(string $string)
    {
        $regex = '/^\d{2}(\d{2}(-|$)){3}/';

        return preg_match($regex, $string);
    }

    /**
     * Format datetime
     * If format is y-m-d
     * Then return $datetime
     * Else return $string
     *
     * @param string $string
     * @param \Carbon\CarbonInterface $datetime
     * @return \Carbon\CarbonInterface
     */
    public static function getDateTimeFormat(string $string, CarbonInterface $datetime)
    {
        return self::isDate($string) ? $datetime : new Carbon($string, self::DEFAULT_TIMEZONE);
    }
}
