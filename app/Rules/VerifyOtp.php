<?php

namespace NeubusSrm\Rules;

use Illuminate\Contracts\Validation\Rule;
use NeubusSrm\Events\NeulogActionEvent;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Repositories\UserRepository;
use NeubusSrm\Services\UserService;
use OTPHP\TOTP;

/**
 * Class VerifyOtp
 * @package NeubusSrm\Rules
 */
class VerifyOtp implements Rule
{

    /**
     * @var string
     */
    public const CURRENT_ATTEMPT_KEY = 'mfa_verification_attempts';

    public const NEEDS_VERIFICATION_KEY = 'needs_verification';

	/**
	 * @var string
	 */
	private $email;

    /**
     * @var User
     */
	private $user;

    /**
     * @var int
     */
	private $maxAttempts;

    /**
     * @var int
     */
	private $currentAttempts;

    /**
     * @var UserRepository
     */
	private $userRepository;

    /**
     * @var UserService
     */
	private $userService;

    /**
     * @var
     */
	private $errorMessage;

    /**
     * VerifyOtp constructor.
     * @param string $email
     * @throws \Throwable
     */
    public function __construct(string $email)
    {
        $this->email = $email;
        $this->userRepository = resolve(UserRepository::class);
        $this->userService = resolve(UserService::class);
        $this->maxAttempts = config('srm.max_mfa_refresh_attempt');
        $this->currentAttempts = session(self::CURRENT_ATTEMPT_KEY);
    }

	/**
	 * @param string $attribute
	 * @param mixed $value
	 * @return bool
	 * @throws \Throwable
	 */
    public function passes($attribute, $value) : ?bool
    {
        $returnBool = false;
    	try {
    		$user = $this->userRepository->getUserByEmailLower($this->email);
		    $hasMfa = $user->has_mfa;
		    if ($hasMfa) {
			    $otpToken = TOTP::create($user->otp_secret);
			    $otpToken->setLabel($user->email);
			    $otpToken->setIssuer('Neubus SRM');
			    if (!$otpToken->verify($value)) {
				    if (\Auth::check() && !request()->routeIs('login.do.refresh')) {
                        event(new NeulogActionEvent('Logout', $this->userRepository->getLoggableParams(\Auth::id())));
					    \Auth::logout();
				    }
				    // for reverification instead of actual logins
                    else if (request()->routeIs(route('login.do.refresh'))) {
                        \Session::put(self::CURRENT_ATTEMPT_KEY,
                            session(self::CURRENT_ATTEMPT_KEY, 0)+1);
                    }
                    else if (session(self::CURRENT_ATTEMPT_KEY) === config('srm.max_mfa_refresh_attempt')) {
                        $this->errorMessage = "You currently have {$this->currentAttempts} out of {$this->maxAttempts} remaining.";
                        event(new NeulogActionEvent('Logout', $this->userRepository->getLoggableParams(\Auth::id())));
                        \Auth::logout();
                    }
                    else {
                        $this->errorMessage = 'Invalid token, please try again.';
                    }
			    }
			    else {
			        if ($user->otp_verified) {
                        \Auth::login($user, true);
                        event(new NeulogActionEvent('Login',
                            $this->userRepository->getLoggableParams(\Auth::id())));
                    }
			        else {
                        event(new NeulogActionEvent('Logout', $this->userRepository->getLoggableParams(\Auth::id())));
			            \Auth::logout();
                    }
                    $this->userService->setVerifyMfa(\Auth::id(), false);
			        $returnBool = true;
                }
		    }
		    else {
                if (!\Auth::check()) {
                    \Auth::login($user, true);
                    event(new NeulogActionEvent('Login',
                        $this->userRepository->getLoggableParams(\Auth::id())));
                    $returnBool = true;
                }
            }
	    }
	    catch (\Throwable $exception) {
    		\Log::error($exception->getMessage());
    		$returnBool = false;
	    }
	    finally {
    	    return $returnBool;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage;
    }
}
