<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NeubusSrm\Rules\ValidIndexType;

/**
 * Class CreateIndexesRequest
 * @package NeubusSrm\Http\Requests
 */
class CreatePartIndexesRequest extends FormRequest
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
            'part_index_value' => ['required']
        ];
    }

	/**
	 * @return array
	 */
	public function messages() {
    	parent::messages();
		return [
			'part_index_value.required' => 'You must give a valid part index value'
		];
	}
}
