<?php

namespace NeubusSrm\Rules;

use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Contracts\Validation\Rule;
use NeubusSrm\Repositories\BoxRepository;


class ValidBoxName implements Rule
{

	/**
	 * @var
	 */
	private $boxName;
    /**
     * Create a new rule instance.
     *
     * @param string $projectId
     * @return void
     */
    public function __construct(string $boxName)
    {
        $this->boxName = $boxName;
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
        $boxRepository = resolve(BoxRepository::class);
        try {
	        $boxChecking = $boxRepository->doesBoxNameNotExist($this->boxName);

            return $boxChecking;
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
        return 'Invalid box name';
    }
}
