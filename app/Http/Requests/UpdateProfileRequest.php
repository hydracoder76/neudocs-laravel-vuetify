<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NeubusSrm\Rules\PasswordValidationRule;

/**
 * Class UpdateProfileRequest
 * @package NeubusSrm\Http\Requests
 */
class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
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
                'min:8',
                new PasswordValidationRule,
                'confirmed'
            ],
            'password_confirmation' => [
                'required_with:password',
                'same:password'
            ],
            'name' => 'filled',
            'email' => 'filled|email'
        ];
    }

    /**
     * @return array
     */
    public function messages() {
        parent::messages();
        return [
            'password.min' => 'Password length must be at least 8 characters',
            'password_confirmation.same' => 'Passwords do not match'
        ];
    }
}
