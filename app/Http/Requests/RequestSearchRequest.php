<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NeubusSrm\Rules\ValidateJsonRule;
use Auth;

/**
 * Class RequestSearchRequest
 * @package NeubusSrm\Http\Requests
 */
class RequestSearchRequest extends FormRequest
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
            'indexes' => ['present', 'json', new ValidateJsonRule('indexes')],
            'projectID' => ['required'],
            'sortBy' => ['present', 'nullable', 'regex:/^[a-zA-Z0-9_\-\s]*$/i'],
            'order' => ['required'],
            'review' => ['required'],
        ];
    }
    /**
     * @return array
     */
    public function messages() {
        parent::messages();
        return [
            'indexes.present' => 'Need search terms to be entered',
            'projectId.required' => 'Need project id',
            'sortBy.present' => 'Need column to sort by, or leave blank',
            'sortBy.regex' => 'Can only sort by alphanumeric columns',
            'order.required' => 'Need order to sort by',
            'review.required' => 'Need if sort by reviewed'
        ];
    }
}
