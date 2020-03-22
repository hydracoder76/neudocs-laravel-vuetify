<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NeubusSrm\Rules\PartTypeRule;

/**
 * Class PartSubmitRequest
 * @package NeubusSrm\Http\Requests
 */
class PartSubmitRequest extends FormRequest
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
            'part_type' => ['required', new PartTypeRule(request()->input('part_type'))],
	        'part_name' => ['required'],
	        'part_name_system' => ['required'],
	        'part_location_code' => ['required']
        ];
    }
}
