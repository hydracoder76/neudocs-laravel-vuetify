<?php

namespace NeubusSrm\Rules;

use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Contracts\Validation\Rule;
use NeubusSrm\Repositories\PartRepository;

/**
 * Class PartTypeRule
 * @package NeubusSrm\Rules
 */
class PartTypeRule implements Rule
{

	/**
	 * @var
	 */
	private $projectId;
    /**
     * Create a new rule instance.
     *
     * @param string $projectId
     * @return void
     */
    public function __construct(string $projectId)
    {
        $this->projectId = $projectId;
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
        $partRepository = resolve(PartRepository::class);
        try {
	        $parts = $partRepository->getPartsByProjectId($this->projectId);

        }
        catch (EntityNotFoundException | \Throwable $exception) {
        	return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid part field type';
    }
}
