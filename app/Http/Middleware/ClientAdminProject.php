<?php

namespace NeubusSrm\Http\Middleware;

use Closure;
use Gate;
use Illuminate\Support\Facades\Auth;
use NeubusSrm\Lib\Constants\HttpConstants;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\Project;

/**
 * Class ClientAdminProject
 * @package NeubusSrm\Http\Middleware
 */
class ClientAdminProject
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
        $role = Auth::user()->role;
        if (($role == User::ROLE_CLIENT || $role == USER::ROLE_ADMIN)){
            $projectName = $request->route('project');
            $companyProject = Project::where('project_name', $projectName)->first();
            if ($companyProject == null || Auth::user()->company_id != $companyProject->company_id){
                abort(HttpConstants::HTTP_FORBIDDEN);
            }
        }
        return $next($request);
    }
}
