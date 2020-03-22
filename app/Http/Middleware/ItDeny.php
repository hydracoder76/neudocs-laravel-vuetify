<?php

namespace NeubusSrm\Http\Middleware;

use Closure;
use NeubusSrm\Lib\Constants\HttpConstants;
use NeubusSrm\Models\Auth\User;

/**
 * Class ItDeny
 * @package NeubusSrm\Http\Middleware
 */
class ItDeny
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Auth::check() && \Auth::user()->role === User::ROLE_IT) {
            abort(HttpConstants::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
