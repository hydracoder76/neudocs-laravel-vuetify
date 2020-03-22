<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DataEntrySearchRequest
 * @package NeubusSrm\Http\Requests
 */
class DataEntrySearchRequest extends FormRequest
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
            'keyword' => ['regex:/^[a-zA-Z0-9_\-\s]*$/i']
        ];
    }
    /**
     * @return array
     */
    public function messages() {
        parent::messages();
        return [
	        'keyword.regex' => 'Keyword can only include alphanumeric, space, dash, and underscore characters',
        ];
    }
}
