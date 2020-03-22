<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NeubusSrm\Rules\ValidateJsonRule;
use Auth;

/**
 * Class ResetPasswordUserRequest
 * @package NeubusSrm\Http\Requests
 */
class ResetPasswordUserRequest extends FormRequest
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
            'email' => ['required', 'email'],
        ];
    }
    /**
     * @return array
     */
    public function messages() {
        parent::messages();
        return [
            'email.required' => 'Need a user email',
            'email.email' => 'Email must be in email format'
        ];
    }
}
