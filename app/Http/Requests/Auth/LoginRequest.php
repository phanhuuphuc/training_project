<?php

namespace App\Http\Requests\Auth;

use App\Enums\ActiveStatus;
use App\Enums\Status;
use App\Models\User;
use App\Rules\RegexEmail;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                new RegexEmail,
            ],
            'password' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'email.email' => trans('validation.email')
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = User::where('email', $this->get('email'))->first();

        switch (true) {
            case !$user:
                throw ValidationException::withMessages([
                    'email' => trans('messages.errors.auth_email_not_exist'),
                ]);
              break;
            case $user->is_active == ActiveStatus::INACTIVE:
                throw ValidationException::withMessages([
                    'email' => trans('messages.errors.auth_user_is_lock'),
                ]);
              break;
            case $user->is_delete == Status::YES:
                throw ValidationException::withMessages([
                    'email' => trans('messages.errors.auth_user_is_deleted'),
                ]);
              break;
        }

        $dataAuth = [
            'email' => $this->get('email'),
            'password' => $this->get('password'),
            'is_active' => ActiveStatus::ACTIVE,
            'is_delete' => Status::NO
        ];

        if (! Auth::attempt($dataAuth, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'password' => trans('messages.errors.auth_password_failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * LogLoginHistory
     *
     */
    public function logLoginHistory(string $ip): void
    {
        $user = Auth::user();
        $user->last_login_at = now();
        $user->last_login_ip = $ip;
        $user->save();
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}
