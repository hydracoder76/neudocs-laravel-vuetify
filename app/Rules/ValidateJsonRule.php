<?php

namespace NeubusSrm\Rules;

use Illuminate\Contracts\Validation\Rule;
use JsonSchema\Validator as JsonValidator;

/**
 * Class ValidateJsonRule
 * @package NeubusSrm\Rules
 */
class ValidateJsonRule implements Rule
{

    /**
     * @var object
     */
    private $schemaDefinition;

    /**
     * @var JsonValidator
     */
    private $jsonValidator;

    /**
     * ValidateJsonRule constructor.
     * @param string $schemaName
     */
    public function __construct(string $schemaName)
    {
        $this->schemaDefinition = json_decode(storage_path("srm/schemas/$schemaName.json"), JSON_FORCE_OBJECT);
        $this->jsonValidator = new JsonValidator();
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
        // may need a few tests to be performed to make sure this works
        $this->jsonValidator->validate($value, ['$ref' => $this->schemaDefinition]);
        return $this->jsonValidator->isValid();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Upload metadata was invalid';
    }
}
