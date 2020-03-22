<?php

namespace NeubusSrm\Rules;

use Illuminate\Contracts\Validation\Rule;
use NeubusSrm\Models\Indexing\IndexType;

/**
 * Class ValidIndexType
 * @package NeubusSrm\Rules
 */
class ValidIndexType implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        if (array_search($value, IndexType::INDEX_TYPES) !== false) {
        	return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid index type specified';
    }
}
