<?php

namespace NeubusSrm\Http\Middleware;

use Closure;
use NeubusSrm\Lib\Exceptions\UserNotFoundException;
use NeubusSrm\Rules\VerifyOtp;
use NeubusSrm\Services\UserService;

/**
 * Class RedirectToVerify
 * @package NeubusSrm\Http\Middleware
 */
class RedirectToVerify
{

    /**
     * @var \NeubusSrm\Models\Auth\User|null
     */
    private $currentUser;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * RedirectToVerify constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService) {
        $this->userService = $userService;
        $this->currentUser = \Auth::user();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws \Throwable
     */
    public function handle($request, Closure $next)
    {
        $forwardRequest = $next($request);
        if (\Auth::check()) {
            try {
                // just want to be super sure that this user is an actual
                // mfa user
                $isTimedOut = $this->userService->authedUserIsTimedOut();
                if ($this->currentUser !== null &&
                    $this->currentUser->has_mfa &&
                    $this->currentUser->otp_secret !== null &&
                    $this->currentUser->otp_verified) {
                    // remember, should only fully timeout and view the verify page if
                    // last verified is also out of date to prevent sessions being restore on page load

                    if ($isTimedOut
                        || $this->currentUser->verify_mfa) {
                        if ($isTimedOut) {
                            $this->userService->setVerifyMfa($this->currentUser->id);
                        }
                        return redirect(route('auth.verify'));
                    }
                }
                // handle the case where we're trying to hit this manually and should not be
                // and since it happens after the check for if we SHOULD be here, the flow
                // will work
                else if (\Route::currentRouteName() === 'auth.verify'){
                    $forwardRequest = redirect($this->userService->getDefaultRoute());
                }
            }
            catch (UserNotFoundException $exception) {
                \Log::error("{$exception->getMessage()} : {$exception->getInternalCode()}");
                abort($exception->getHttpCode());
            }
        }
        return $forwardRequest;
    }
}
