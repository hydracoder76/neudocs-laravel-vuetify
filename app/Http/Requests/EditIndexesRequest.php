<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NeubusSrm\Rules\ValidIndexType;

/**
 * Class EditIndexesRequest
 * @package NeubusSrm\Http\Requests
 */
class EditIndexesRequest extends FormRequest
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
            'id' => ['required', 'exists:index_types,id'],
            'index_name' => ['required'],
            'internal_name' => ['required'],
            'description' => ['required'],
        ];
    }

    /**
     * @return array
     */
    public function messages() {
        parent::messages();
        return [
            'id.required' => 'You must give a valid index id',
            'index_name.required' => 'You must give a valid index name',
            'internal_name.required' => 'You must give a valid internal name',
            'description.required' => 'You must give a valid description',
        ];
    }
}
