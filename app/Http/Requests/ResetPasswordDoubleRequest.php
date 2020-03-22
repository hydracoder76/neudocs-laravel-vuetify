<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NeubusSrm\Rules\ValidateJsonRule;
use NeubusSrm\Rules\PasswordValidationRule;
use Auth;

/**
 * Class ResetPasswordDoubleRequest
 * @package NeubusSrm\Http\Requests
 */
class ResetPasswordDoubleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => [
                'required',
                'min:8',
                new PasswordValidationRule,
                'confirmed'
            ],
            'password_confirmation' => [
                'required_with:password',
                'same:password'
            ]
        ];
    }
    /**
     * @return array
     */
    public function messages() {
        parent::messages();
        return [
            'password.required' => 'Password field is required',
            'password.min' => 'Password length must be at least 8 alphanumeric characters',
            'password_confirmation.same' => 'Passwords do not match'
        ];
    }
}
