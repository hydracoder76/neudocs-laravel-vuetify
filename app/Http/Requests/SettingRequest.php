<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use NeubusSrm\Rules\ValidateJsonRule;
use NeubusSrm\Rules\ValidateJsonSettingRule;

/**
 * Class SettingRequest
 * @package NeubusSrm\Http\Requests
 */
class SettingRequest extends FormRequest
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
            'settings' => ['required', new ValidateJsonRule('settings')],
            'project_id' => ['required'],
        ];
    }
    /**
     * @return array
     */
    public function messages() {
        parent::messages();
        return [
            'settings.required' => 'Need settings to be saved',
            'project_id.required' => 'Needs project selection'
        ];
    }
}
