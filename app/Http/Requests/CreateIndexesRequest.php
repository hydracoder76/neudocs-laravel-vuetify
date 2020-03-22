<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NeubusSrm\Rules\ValidIndexType;

/**
 * Class CreateIndexesRequest
 * @package NeubusSrm\Http\Requests
 */
class CreateIndexesRequest extends FormRequest
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
	        'index_name' => ['required'],
	        'project_id' => ['required', 'alpha_dash'],
	        'validation_regex' => ['required_if:has_validation,yes']
        ];
    }

	/**
	 * @return array
	 */
	public function messages() {
    	parent::messages();
		return [
			'index_name.required' => 'You must give a valid index name',
			'project_id.required' => 'You must give a valid project id',
			'project_id.alpha_dash' => 'Invalid  characters in project id'
		];
	}
}
