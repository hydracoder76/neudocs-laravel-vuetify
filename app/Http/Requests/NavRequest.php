<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class NavRequest
 * @package NeubusSrm\Http\Requests
 */
class NavRequest extends FormRequest
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
            'parts' => ['required'],
            'action' => ['required'],
        ];
    }
    /**
     * @return array
     */
    public function messages() {
        parent::messages();
        return [
            'parts.required' => 'Needs parts list',
            'action.required' => 'Needs a page to navigate to',
        ];
    }
}
