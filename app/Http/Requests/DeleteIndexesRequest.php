<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NeubusSrm\Rules\ValidIndexType;

/**
 * Class DeleteIndexesRequest
 * @package NeubusSrm\Http\Requests
 */
class DeleteIndexesRequest extends FormRequest
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
            'id' => ['required', 'exists:index_types,id']
        ];
    }

    /**
     * @return array
     */
    public function messages() {
        parent::messages();
        return [
            'id.required' => 'You must give a valid index id'
        ];
    }
}
