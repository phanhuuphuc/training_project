<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\BaseRequest;
use App\Rules\RegexEmail;
use App\Rules\RegexTelNum;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends BaseRequest
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
            'customer_name' => [
                'bail',
                'required',
                'string',
                'max:' . self::MAX_LENGTH_NAME,
                'min:' . self::MIN_LENGTH_NAME,
            ],
            'email' => [
                'bail',
                'required',
                'string',
                'max:' . self::MAX_LENGTH_EMAIL,
                new RegexEmail,
                Rule::unique('mst_customers', 'email')
            ],
            'tel_num' => [
                'bail',
                'required',
                'string',
                new RegexTelNum,
                'max:' . self::MAX_LENGTH_TEL_NUM,
                'min:' . self::MIN_LENGTH_TEL_NUM,
            ],
            'address' => [
                'bail',
                'required',
                'string',
                'max:' . self::MAX_LENGTH_TEXT,
            ],
            'is_active' => [
                'bail',
                'boolean'
            ]
        ];
    }
}
