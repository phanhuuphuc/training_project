<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;

class ListProductRequest extends BaseRequest
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
        return array_merge($this->commonListRules(), [
            'product_name' => [
                'nullable',
                'string'
            ],
            'product_min_price' => [
                'nullable',
                'numeric',
            ],
            'product_max_price' => [
                'nullable',
                'numeric',
            ],
            'is_sales' => [
                'nullable',
                'integer',
            ]
        ]);
    }
}
