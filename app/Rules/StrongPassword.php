<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    /**
     * Keys for any checks that failed.
     *
     * @var array
     */
    protected $failures = [];

    /**
     * Run each check and collect failures.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->failures = [];

        $checks = [
            'uppercase' => '/[A-Z]/',
            'number'    => '/\d/',
            'special'   => '/[\W_]/',
        ];

        foreach ($checks as $key => $pattern) {
            if (! preg_match($pattern, $value)) {
                $this->failures[] = $key;
            }
        }

        return empty($this->failures);
    }

    /**
     * Build a combined message based on which checks failed.
     *
     * @return string
     */
    public function message()
    {
        $map = [
            'uppercase' => 'an uppercase letter',
            'number'    => 'a number',
            'special'   => 'a special character',
        ];

        $phrases = array_map(fn($f) => $map[$f], $this->failures);

        if (count($phrases) === 1) {
            return "Password must contain {$phrases[0]}.";
        }

        // If more than one failed, join with commas and 'and'
        $last = array_pop($phrases);
        $list = implode(', ', $phrases) . " and {$last}.";

        return "Password must contain {$list}";
    }
}
