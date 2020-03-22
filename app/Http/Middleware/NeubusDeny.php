<?php

namespace NeubusSrm\Http\Middleware;

use Closure;
use NeubusSrm\Lib\Constants\HttpConstants;
use NeubusSrm\Models\Auth\User;

/**
 * Class NeubusDeny
 * @package NeubusSrm\Http\Middleware
 */
class NeubusDeny
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
        if (\Auth::check() && \Auth::user()->role === User::ROLE_NEUBUS) {
            abort(HttpConstants::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
