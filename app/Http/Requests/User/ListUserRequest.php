<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class ListUserRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
            'name' => [
                'nullable',
                'string'
            ],
            'email' => [
                'nullable',
                'string',
            ],
            'group_role' => [
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
