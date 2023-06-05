<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** @SuppressWarnings(PHPMD.NumberOfChildren) */
class BaseRequest extends FormRequest
{
    const DEFAULT_PER_PAGE = 20;

    const MIN_LENGTH_EMAIL = 3;
    const MAX_LENGTH_EMAIL = 250;

    const MIN_LENGTH_PASSWORD = 6;
    const MAX_LENGTH_PASSWORD = 50;
    const MIN_LENGTH_NAME = 6;
    const MAX_LENGTH_NAME = 255;
    const MIN_LENGTH_TEL_NUM = 7;
    const MAX_LENGTH_TEL_NUM = 14;
    const MIN_PRICE = 0;
    const MAX_PRICE = 99999999;


    const INT_32_MIN = 1;
    const LIMIT_DEFAULT_MAX = 100;
    const ORDER_DEFAULT_LENGTH = 100;
    const WITH_DEFAULT_LENGTH = 500;
    const MAX_LENGTH_TEXT = 255;
    const MAX_SIZE_IMAGE = 2130;
    const MAX_WIDTH_IMAGE = 1024;
    const MAX_HEIGHT_IMAGE = 1024;
    const MAX_SIZE_CSV = 5130;

    /**
     * Common list rules
     *
     * @return array
     */
    public function commonListRules()
    {
        return [
            'page' => [
                'bail',
                'sometimes',
                'integer',
            ],
            'per_page' => [
                'bail',
                'sometimes',
                'integer',
                'min:' . self::INT_32_MIN,
                'max:' . static::LIMIT_DEFAULT_MAX
            ],
            'order' => [
                'bail',
                'sometimes',
                'string',
                'max:' . self::ORDER_DEFAULT_LENGTH
            ],
            'with' => [
                'bail',
                'sometimes',
                'string',
                'max:' . self::WITH_DEFAULT_LENGTH
            ]
        ];
    }

    public function messages()
    {
        return [
            // phpcs:ignore
            '*name.min' => trans('validation.bigger', [
                'min' => self::MIN_LENGTH_NAME - 1
            ]),
            // phpcs:ignore
            '*name.max' => trans('validation.smaller', [
                'max' => self::MAX_LENGTH_NAME + 1
            ])
        ];
    }
}
