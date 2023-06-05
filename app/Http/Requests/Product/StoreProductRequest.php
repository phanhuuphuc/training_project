<?php

namespace App\Http\Requests\Product;

use App\Enums\IsSalesStatus;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends BaseRequest
{
    /**
     * Determine if the product is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_name' => [
                'bail',
                'required',
                'string',
                'min:' . self::MIN_LENGTH_NAME,
                'max:' . self::MAX_LENGTH_NAME,
            ],
            'product_image' => [
                'bail',
                'nullable',
                'file',
                'mimes:jpg,jpeg,png',
                'max:' . self::MAX_SIZE_IMAGE,
                'image',
                'dimensions:max_width=' . self::MAX_WIDTH_IMAGE . ',max_height=' . self::MAX_WIDTH_IMAGE,
            ],
            'product_price' => [
                'bail',
                'required',
                'numeric',
                'min:'. self::MIN_PRICE,
                'max:' . self::MAX_PRICE,
            ],
            'is_sales' => [
                'bail',
                'required',
                Rule::in(IsSalesStatus::getAllIsSalesStatus()),
            ],
            'description' => [
                'bail',
                'nullable',
                'string'
            ]
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            // phpcs:ignore
            'product_image.dimensions' => trans('validation.product_image_dimensions', [
                'max_width_image' => self::MAX_WIDTH_IMAGE
            ])
        ]);
    }
}
