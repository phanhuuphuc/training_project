<?php

namespace App\Http\Requests\User;

use App\Enums\GroupRole;
use App\Http\Requests\BaseRequest;
use App\Rules\RegexEmail;
use App\Rules\RegexPassword;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends BaseRequest
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
        // phpcs:ignore
        $userId = request()->route('user');
        return [
            'name' => [
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
                Rule::unique('mst_users')->ignore($userId ? $userId : 0, 'id')
            ],
            'password' => [
                'bail',
                'sometimes',
                'min:' . self::MIN_LENGTH_PASSWORD,
                'max:' . self::MAX_LENGTH_PASSWORD,
                new RegexPassword,
                'confirmed',
            ],
            'password_confirmation' => [
                'bail',
                'sometimes',
                'min:' . self::MIN_LENGTH_PASSWORD,
                'max:' . self::MAX_LENGTH_PASSWORD,
            ],
            'group_role' => [
                'bail',
                Rule::in(GroupRole::getGroupRole())
            ],
            'is_active' => [
                'bail',
                'boolean'
            ]
        ];
    }
}
