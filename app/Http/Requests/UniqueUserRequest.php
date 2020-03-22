<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UniqueUserRequest
 * @package NeubusSrm\Http\Requests
 */
class UniqueUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        // there are some cases where login is not required
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'email' => 'unique:users,email'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array {
        return [
            'email.unique' => 'This user already exists'
        ];
    }
}
