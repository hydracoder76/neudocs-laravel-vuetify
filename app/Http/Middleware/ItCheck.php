<?php

namespace NeubusSrm\Http\Middleware;

use Closure;
use Gate;
use NeubusSrm\Lib\Constants\HttpConstants;

/**
 * Class ItCheck
 * @package NeubusSrm\Http\Middleware
 */
class ItCheck
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
    	if (Gate::allows('it.menu')) {
		    return $next($request);
	    }

    	abort(HttpConstants::HTTP_FORBIDDEN);
    }
}
