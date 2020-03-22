<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NeubusSrm\Rules\ValidateJsonRule;
use Auth;

/**
 * Class ProjectBoxRequest
 * @package NeubusSrm\Http\Requests
 */
class GetPartsByBoxRequest extends FormRequest
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
            'keyword' => ['present', 'nullable', 'regex:/^[a-zA-Z0-9_\-\s]*$/i'],
            'boxId' => ['required'],
            'sortBy' => ['present', 'nullable', 'regex:/^[a-zA-Z0-9_\-\s]*$/i'],
            'order' => ['required']
        ];
    }
    /**
     * @return array
     */
    public function messages() {
        parent::messages();
        return [
            'keyword.present' => 'Need search term or blank filler',
            'keyword.regex' => 'Keyword can only include alphanumeric, space, dash, and underscore characters',
            'boxId.required' => 'Need box id',
            'sortBy.present' => 'Need column to sort by, or leave blank',
            'sortBy.regex' => 'Can only sort by alphanumeric columns',
            'order.required' => 'Need order to sort by'
        ];
    }
}
