<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class RegexPassword implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     * @SuppressWarnings("unused")
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/", $value)) {
            $fail(trans('validation.regex_password')); // phpcs:ignore
        }
    }
}
