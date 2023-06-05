<?php

namespace App\Filters;

use App\Common\StrReplace;
use Mi\L5Core\Filters\BaseFilter;

class Email extends BaseFilter
{
    /**
     * Apply the filter
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param mixed $input
     * @return mixed
     */
    public static function apply($model, $input)
    {
        $input = StrReplace::escapeBeforeSearch(strtolower($input));
        $input2 = StrReplace::escapeBeforeSearch($input);

        return $model->where(function ($query) use ($input, $input2) {
            $query->whereRaw('lower(email) like (?)', "%{$input}%")
                ->orWhereRaw('email like (?)', "%{$input2}%");
        });
    }
}
