<?php

namespace NeubusSrm\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;


/**
 * Class MaxFileSizeRule
 * @package NeubusSrm\Rules
 */
class MaxFileSizeRule implements Rule
{

    private $fileName;
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
        $this->fileName = $value->getSize();
        return $value->getSize() < (int) config('srm.max_upload_size');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The file {$this->fileName} is over the maximum allowed file size and was not uploaded.";
    }
}
