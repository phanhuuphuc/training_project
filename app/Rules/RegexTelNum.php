<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class RegexTelNum implements InvokableRule
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
        if (!preg_match("/^\d{9,14}$/", $value)) {
            $fail(trans('validation.regex_telnum')); // phpcs:ignore
        }
    }
}
