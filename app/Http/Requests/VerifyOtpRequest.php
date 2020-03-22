<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NeubusSrm\Rules\VerifyOtp;

/**
 * Class VerifyOtpRequest
 * @package NeubusSrm\Http\Requests
 */
class VerifyOtpRequest extends FormRequest
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
     * @return array
     * @throws \NeubusSrm\Lib\Exceptions\NeuSrmException
     * @throws \Throwable
     */
    public function rules()
    {
        return [
        	'email' => ['present', 'email'],
            'token' => ['required', 'numeric', new VerifyOtp(request()->input('email'))],
            'verification_token' => ['sometimes'],
            'confirm' => ['sometimes', 'boolean']
        ];
    }

	/**
	 * @return array
	 */
    public function messages() {
	    return [
	    	'email.present' => 'Email required',
		    'email.email' => 'Email was invalid',
		    'token.required' => 'An MFA token is required',
		    'token.numeric' => 'Token contains invalid characters',
            'verification_token.sometimes' => 'Valid verification token is required for this request',
            'confirm.sometimes' => 'Please set a confirmation flag',
            'confirm.boolean' => 'Please use a valid boolean value'
	    ];
    }
}
