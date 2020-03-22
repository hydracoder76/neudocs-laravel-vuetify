<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NeuSrmCreateUserRequest extends FormRequest
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
          'name' => ['required'],
          'email' => ['required'],
          'password' => ['present'],
          'company_id' => ['required'],
          'role' => ['required'],
        ];
    }

    /**
     * @return array
     */
    public function messages(){
        return [
            'name.required' => 'Name is required',
            'email.required' => 'The search field must be a string',
            'company_id.required' => 'The value being searched for is required',
            'role.required' => 'The value being searched for must be a string',
        ];
    }
}
