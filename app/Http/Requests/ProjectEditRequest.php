<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ProjectEditRequest
 * @package NeubusSrm\Http\Requests
 */
class ProjectEditRequest extends FormRequest
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
            'project_name' => ['required', 'regex:/^[a-zA-Z0-9_\-\s]*$/i']
        ];
    }
    /**
     * @return array
     */
    public function messages() {
        parent::messages();
        return [
            'project_name.regex' => 'Project name can only include alphanumeric, space, dash, and underscore characters',
            'project_name.required' => 'Project names are required'
        ];
    }
}
