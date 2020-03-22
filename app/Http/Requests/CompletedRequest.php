<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CompletedRequest
 * @package NeubusSrm\Http\Requests
 */
class CompletedRequest extends FormRequest
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
            'sortBy' => ['present', 'nullable', 'regex:/^[a-zA-Z0-9_\-\s]*$/i'],
            'filter_by' => ['present'],
            'project' => ['required'],
            'order' => ['required']
        ];
    }
    /**
     * @return array
     */
    public function messages() {
        parent::messages();
        return [
            'sortBy.present' => 'Order By is required if specified',
            'filter_by.present' => 'Needs a filter query',
            'sortBy.regex' => 'Order by values must be composed of alphanumeric and space characters',
            'project.required' => 'Must have project',
            'order.required' => 'Must have sort order'
        ];
    }
}
