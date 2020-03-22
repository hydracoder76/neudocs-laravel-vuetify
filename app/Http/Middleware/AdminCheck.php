<?php

namespace NeubusSrm\Http\Middleware;

use Closure;
use Gate;
use NeubusSrm\Lib\Constants\HttpConstants;

/**
 * Class AdminCheck
 * @package NeubusSrm\Http\Middleware
 */
class AdminCheck
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
    	if (Gate::allows('it.menu') || Gate::allows('admin.menu')) {
		    return $next($request);
	    }

    	abort(HttpConstants::HTTP_FORBIDDEN);
    }
}
