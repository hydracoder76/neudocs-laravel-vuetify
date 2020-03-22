<?php

namespace NeubusSrm\Http\Middleware;

use Closure;

/**
 * Class ForceHttps
 * @package OneCase\Http\Middleware
 */
class ForceHttps
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
        if (!$request->isSecure() &&  \App::environment() !== 'testing') {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);

    }
}
