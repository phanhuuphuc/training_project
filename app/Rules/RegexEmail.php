<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class RegexEmail implements InvokableRule
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
        if (!preg_match("/^[\w\-\.]+@[\w\-\.]+\.[\w\-\.]+$/", $value)) {
            $fail(trans('validation.regex_email')); // phpcs:ignore
        }
    }
}
