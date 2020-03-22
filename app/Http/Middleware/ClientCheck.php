<?php

namespace NeubusSrm\Http\Middleware;

use Closure;
use Gate;
use NeubusSrm\Lib\Constants\HttpConstants;

/**
 * Class NeubusCheck
 * @package NeubusSrm\Http\Middleware
 */
class ClientCheck
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
    	if (Gate::allows('client.menu')||
            Gate::allows('admin.menu') ||
            Gate::allows('it.menu') ||
            Gate::allows('neubus.menu'))  {

		    return $next($request);
	    }
    	abort(HttpConstants::HTTP_FORBIDDEN);
    }
}
