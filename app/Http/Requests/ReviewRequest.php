<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ReviewRequest
 * @package NeubusSrm\Http\Requests
 */
class ReviewRequest extends FormRequest
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
            'requestId' => ['required'],
            'comment' => ['nullable'],
            'accept' => ['required']
        ];
    }
    /**
     * @return array
     */
    public function messages() {
        parent::messages();
        return [
            'requestId.required' => 'Need request ID',
            'comment.present' => 'Need comment',
            'accept.required' => 'Need accept or deny'
        ];
    }
}
