<?php

namespace NeubusSrm\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class PasswordValidationRule
 * @package NeubusSrm\Rules
 */
class PasswordValidationRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * One lowercase letter required.
     *
     * @param $value
     * @return int
     */
    public function validatePasswordOnLowerCase($value) : int
    {
        return preg_match("/[a-z]+/", $value);
    }

     /**
     * One uppercase letter required.
     *
     * @param $value
     * @return int
     */
    public function validatePasswordOnUpperCase($value) : int
    {
        return preg_match("/[A-Z]+/", $value);
    }

    /**
     * Password must have at least 2 numeric characters.
     *
     * @param $value
     * @return int
     */
    public function validatePasswordOnTwoNumericValues($value) : int
    {
        return preg_match("/.*[0-9].*[0-9].*/", $value);
    }

    /**
     * Numeric characters must not be at the beginning or the ending of password.
     *
     * @param $value
     * @return int
     */
    public function validatePasswordOnBeginningEndingNumericValue($value) : int
    {
        return preg_match("/^[^0-9].*[^0-9]$/", $value);
    }

    /**
     * Password must include at least one special character from the following list: !@#$%^&*()_+-={}|[]~`:";'<>?,./
     *
     * @param $value
     * @return int
     */
    public function validatePasswordOnSpecialCharacterValue($value) : int
    {
        return preg_match("/[!@#$%^&*()_\+\-\=\{\}|\[\]~`:\";'<>?,.\/]+/", $value);
    }

    /**
     * Password must not contain spaces.
     *
     * @param $value
     * @return int
     */
    public function validatePasswordOnSpaces($value) : int
    {
        return preg_match("/^\S*$/", $value);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) : bool
    {
        return (
            $this->validatePasswordOnLowerCase($value) &&
            $this->validatePasswordOnUpperCase($value) &&
            $this->validatePasswordOnTwoNumericValues($value) &&
            $this->validatePasswordOnBeginningEndingNumericValue($value) &&
            $this->validatePasswordOnSpecialCharacterValue($value) &&
            $this->validatePasswordOnSpaces($value)
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Password violates the password rules.';
    }
}
