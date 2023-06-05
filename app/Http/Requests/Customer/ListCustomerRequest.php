<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\BaseRequest;

class ListCustomerRequest extends BaseRequest
{
    /**
     * Determine if the customer is authorized to make this request.
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
            'customer_name' => [
                'nullable',
                'string'
            ],
            'email' => [
                'nullable',
                'string',
            ],
            'address' => [
                'nullable',
                'string',
            ],
            'is_active' => [
                'nullable',
                'integer',
            ]
        ]);
    }
}
