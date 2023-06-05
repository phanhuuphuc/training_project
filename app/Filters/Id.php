<?php

namespace App\Filters;

use Mi\L5Core\Filters\BaseFilter;

class Id extends BaseFilter
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
        return $model->where('id', $input);
    }
}
