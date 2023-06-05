<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\BaseRequest;

class ImportCustomerRequest extends BaseRequest
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
        return [
            'file_csv' => [
                'required',
                'bail',
                'file',
                'mimes:csv,txt',
                'max:' . self::MAX_SIZE_CSV,
            ],
        ];
    }
}
