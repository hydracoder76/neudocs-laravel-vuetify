<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class NeuSrmLoginRequest
 * @package NeubusSrm\Http\Requests
 */
class NeuSrmLoginRequest extends FormRequest
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
            'email' => ['required', 'email'],
	        'password' => ['required']
        ];
    }

	/**
	 * @return array
	 */
    public function messages() {
	    return [
	    	'email.required' => 'Please provide a valid email',
		    'email.email' => 'Please provide a valid email',
		    'email.exists' => 'No user exists with that login',
		    'password' => 'Please provide a password'
	    ];
    }
}
