<?php

namespace NeubusSrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LogRequest
 * @package NeubusSrm\Http\Requests
 */
class LogRequest extends FormRequest
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
            'dateFrom' => ['present', 'nullable'],
            'dateTo' => ['present', 'nullable'],
            'sortBy' => ['present', 'nullable', 'regex:/^[a-zA-Z0-9_\-\s]*$/i'],
            'order' => ['required']
        ];
    }
    /**
     * @return array
     */
    public function messages() {
        parent::messages();
        return [
            'dateFrom.present' => 'Need date from',
            'dateTo.present' => 'Need date from',
            'sortBy.present' => 'Need column to sort by, or leave blank',
            'sortBy.regex' => 'Can only sort by alphanumeric columns',
            'order.required' => 'Need order to sort by'
        ];
    }
}
