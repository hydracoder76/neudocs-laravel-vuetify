<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestAutoRequest extends FormRequest
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
            'key' => ['required', 'string'],
            'value' => ['present','string','nullable'],
        ];
    }

    /**
     * @return array
     */
    public function messages(){
        return [
            'key.required' => 'The search field is required',
            'key.string' => 'The search field must be a string',
            'value.present' => 'The value being searched for is required',
            'value.string' => 'The value being searched for must be a string',
        ];
    }
}
