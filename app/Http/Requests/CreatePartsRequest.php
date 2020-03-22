<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NeubusSrm\Rules\ValidateJsonRule;
use NeubusSrm\Rules\ValidIndexType;

/**
 * Class CreateIndexesRequest
 * @package NeubusSrm\Http\Requests
 */
class CreatePartsRequest extends FormRequest
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
            'part_name' => ['required', new ValidateJsonRule('data_entry')]
        ];
    }

	/**
	 * @return array
	 */
	public function messages() {
    	parent::messages();
		return [
			'part_name.required' => 'You must give a valid part name'
		];
	}
}
