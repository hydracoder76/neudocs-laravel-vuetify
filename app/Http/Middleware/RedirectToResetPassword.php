<?php

namespace NeubusSrm\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Gate;
use NeubusSrm\Lib\Constants\HttpConstants;

/**
 * Class RedirectToResetPassword
 * @package NeubusSrm\Http\Middleware
 */
class RedirectToResetPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (Auth::user()->is_temp) {
            return redirect(route('reset.password'));
        } else {
            return $next($request);
        }
    }
}
