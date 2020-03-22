<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NeubusSrm\Rules\MaxFileSizeRule;
use NeubusSrm\Rules\ValidateJsonRule;

/**
 * Class UploadFileRequest
 * @package NeubusSrm\Http\Requests
 */
class UploadFileRequest extends FormRequest
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
            'file_meta_data' => ['json', 'required', new ValidateJsonRule('upload')],
            'file_data' => ['required', 'file', new MaxFileSizeRule()],
            'project_id' => ['present']
        ];
    }

    /**
     * @return array
     */
    public function messages() {
        return [
            'file_data.required' => 'Metadata is required for all file uploads',
            'file_data.file' => 'The file you tried to upload was invalid',
            'project_id.present' => 'A project needs to be selected to upload this file'
        ];
    }
}
