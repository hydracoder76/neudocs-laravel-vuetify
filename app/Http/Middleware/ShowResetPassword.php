<?php

namespace NeubusSrm\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Gate;
use NeubusSrm\Lib\Constants\HttpConstants;

/**
 * Class ShowResetPassword
 * @package NeubusSrm\Http\Middleware
 */
class ShowResetPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!Auth::user()->is_temp) {
            abort(HttpConstants::HTTP_FORBIDDEN);
        } else {
            return $next($request);
        }


    }
}

