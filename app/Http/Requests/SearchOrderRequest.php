<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SearchOrderRequest
 * @package NeubusSrm\Http\Requests
 */
class SearchOrderRequest extends FormRequest
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
            'keyword' => ['present', 'nullable', 'regex:/^[a-zA-Z0-9_\-\s]*$/i'],
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
            'sortBy.present' => 'Need column to sort by, or leave blank',
            'sortBy.regex' => 'Can only sort by alphanumeric columns',
            'order.required' => 'Need order to sort by'
        ];
    }
}
