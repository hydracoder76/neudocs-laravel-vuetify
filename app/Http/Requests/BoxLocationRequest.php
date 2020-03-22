<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NeubusSrm\Rules\BoxActivityRule;

/**
 * Class BoxLocationRequest
 * @package NeubusSrm\Http\Requests
 */
class BoxLocationRequest extends FormRequest
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
            'box_name' => ['required'],
            'activity' => ['required', 'string', 'bail', new BoxActivityRule()],
            'location' => ['required']
        ];
    }
    /**
     * @return array
     */
    public function messages() {
        parent::messages();
        return [
	        'box_name.required' => 'Please enter a Box Name',
            'activity.required' => 'Please enter an Activity',
            'location.required' => 'Please enter a Location'
        ];
    }
}
