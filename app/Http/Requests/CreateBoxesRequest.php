<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NeubusSrm\Rules\ValidateJsonRule;
use NeubusSrm\Rules\ValidIndexType;
use NeubusSrm\Rules\ValidBoxName;

/**
 * Class CreateIndexesRequest
 * @package NeubusSrm\Http\Requests
 */
class CreateBoxesRequest extends FormRequest
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
        $request = $this->all('box_name');
        $lowerBoxNameRequest = strtolower($request['box_name']);
        return [
            'box_name' => [new ValidBoxName($lowerBoxNameRequest),'required', new ValidateJsonRule('data_entry')]
        ];
    }

	/**
	 * @return array
	 */
	public function messages() {
    	parent::messages();
		return [
			'box_name.required' => 'You must give a valid box name'
		];
	}
}
