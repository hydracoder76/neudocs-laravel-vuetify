<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use NeubusSrm\Rules\ValidateJsonRule;
use NeubusSrm\Rules\ValidateJsonSettingRule;

/**
 * Class GetSettingsRequest
 * @package NeubusSrm\Http\Requests
 */
class GetSettingsRequest extends FormRequest
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
            'project_id' => ['required'],
        ];
    }
    /**
     * @return array
     */
    public function messages() {
        parent::messages();
        return [
            'project_id.required' => 'Needs project selection'
        ];
    }
}
