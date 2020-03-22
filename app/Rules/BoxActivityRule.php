<?php

namespace NeubusSrm\Rules;

use Illuminate\Contracts\Validation\Rule;
use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;
use NeubusSrm\Models\Org\BoxLocationHistory;
use NeubusSrm\Repositories\BoxRepository;

/**
 * Class BoxActivityRule
 * @package NeubusSrm\Rules
 */
class BoxActivityRule implements Rule
{
    /**
     * @var Box
     */
    protected $box;

    /**
     * @var string
     */
    protected $errorMsg = '';

    /**
     * for NCHECKOUT
     * @var string
     */
    protected $boxNotHasLocationSpecified = 'This box has already been checked in to a location %s. 
    Please check out first before checking in again.';

    protected $boxLocationEmpty = 'This box has already been checked out. 
    Please check in first before checking out again.';

    /**
     * for NCHECKIN
     * @var string
     */
    protected $boxNotHasLocation = 'The location entered does not match the current location for the box %s';

    /**
     * @var array
     */
    protected $activities = [
        BoxLocationHistory::CHECKIN_ACTIVITY,
        BoxLocationHistory::CHECKOUT_ACTIVITY
    ];

    /**
     * @var BoxRepository
     */
    protected $boxRepository;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->boxRepository = resolve(BoxRepository::class);
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
        $validActivity = in_array($value, $this->activities, true);
        $validLocation = $this->checkLocation($value);

        // override the error message if the box is empty so that
        // we don't report twice
        if (!$validActivity) {
            $this->errorMsg = 'Invalid Activity';
        }
        return $validLocation && $validActivity;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMsg;
    }

    /**
     * @param string $activity
     * @return bool
     */
    protected function checkLocation(string $activity): bool {
        $result = false;
        $boxName = request()->input('box_name');
        try {

            // getting box by name sounds risky, but they should be unique?

            $box = $this->boxRepository->getBoxByName($boxName);
            $location = request()->input('location') ?? '';

            switch ($activity) {
                case BoxLocationHistory::CHECKOUT_ACTIVITY:
                    if ($box->box_location_code !== '' && $box->box_location_code !== $location
                        && !empty($location)) {
                        $this->errorMsg = sprintf($this->boxNotHasLocation, "($box->box_location_code)");
                        $result = false;
                    }
                    else if ($box->box_location_code === '') {
                        $this->errorMsg = $this->boxLocationEmpty;
                        $result = false;
                    }
                    else if (empty($location)) {
                        $this->errorMsg = '';
                        $result = false;
                    }
                    else {
                        $result = true;
                    }
                    break;
                case BoxLocationHistory::CHECKIN_ACTIVITY:
                    if ($box->box_location_code !== '') {
                        $result = false;
                        $this->errorMsg = sprintf($this->boxNotHasLocationSpecified, "($box->box_location_code)");
                    }
                    else if (empty($location)) {
                        $this->errorMsg = '';
                        $result = false;
                    }
                    else {
                        $result = true;
                    }
                    break;
                default:
                    $this->errorMsg = 'Invalid activity';
                    $result = false;
            }

        } catch (NeuEntityNotFoundException $exception) {
            if (!empty($boxName)) {
                $this->errorMsg = $exception->getMessage();
            }
            $result = false;
        }
        catch (\Throwable $exception) {
            $this->errorMsg = 'An unknown error occurred';
            $result = false;
        }
        finally {
            return $result;
        }
    }
}
