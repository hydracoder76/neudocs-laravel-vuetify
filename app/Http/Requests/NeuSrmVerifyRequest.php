<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class NeuSrmVerifyRequest
 * @package NeubusSrm\Http\Requests
 */
class NeuSrmVerifyRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'verify_token' => ['required'],
	        'email' => ['required', 'email']
        ];
    }

	/**
	 * @return array
	 */
    public function messages() {
    	parent::messages();
	    return [
		    'verify_token.required' => 'Verification token is required',
		    'email.required' => 'Email is required with request',
		    'email.email' => 'Email was not a valid email'
	    ];
    }
}
