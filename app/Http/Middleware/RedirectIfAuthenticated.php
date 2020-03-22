<?php

namespace NeubusSrm\Http\Middleware;

use Closure;
use Auth;
use Gate;
use NeubusSrm\Lib\Constants\HttpConstants;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Rules\VerifyOtp;
use NeubusSrm\Services\UserService;

/**
 * Class RedirectIfAuthenticated
 * @package NeubusSrm\Http\Middleware
 */
class RedirectIfAuthenticated
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @param  string|null $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null) {
	    $userService = resolve(UserService::class);
        try {
            if (Auth::check()) {

                if ($userService->authedUserIsTimedOut()) {
                    $userService->setVerifyMfa(Auth::id());
                }
            }
        }
        catch (NeuSrmException $exception) {
	        abort($exception->getHttpCode());
        }

		if (Auth::guard($guard)->check()) {
		    $redirectPath = null;
			if (Gate::allows('it.menu')) {
				$redirectPath  = redirect(route('admin.users'));
			}
			else if ( Gate::allows('admin.menu')) {
				$redirectPath = redirect(route('requests'));
			} elseif (Gate::allows('client.menu')) {
                $redirectPath = redirect(route('requests'));
            } elseif (Gate::allows('neubus.menu')) {
                $redirectPath = redirect(route('todo.home'));
			} else {
				abort(HttpConstants::HTTP_FORBIDDEN);
			}
			return $redirectPath;
		} else {
			return $next($request);
		}


	}
}
