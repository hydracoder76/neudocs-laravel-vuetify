<?php

namespace NeubusSrm\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

/**
 * Class Kernel
 * @package NeubusSrm\Http
 */
class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \NeubusSrm\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \NeubusSrm\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \NeubusSrm\Http\Middleware\TrustProxies::class,
        \NeubusSrm\Http\Middleware\ForceHttps::class,
	    \Illuminate\Session\Middleware\StartSession::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \NeubusSrm\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,

            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \NeubusSrm\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \NeubusSrm\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \NeubusSrm\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
	    'it.check' => \NeubusSrm\Http\Middleware\ItCheck::class,
	    'admin.check' => \NeubusSrm\Http\Middleware\AdminCheck::class,
	    'neubus.check' => \NeubusSrm\Http\Middleware\NeubusCheck::class,
        'client.check' => \NeubusSrm\Http\Middleware\ClientCheck::class,
        'show.reset.password' => \NeubusSrm\Http\Middleware\ShowResetPassword::class,
        'reset.password' => \NeubusSrm\Http\Middleware\RedirectToResetPassword::class,
        'neubus.deny' => \NeubusSrm\Http\Middleware\NeubusDeny::class,
        'admin.deny' => \NeubusSrm\Http\Middleware\ClientAdminDeny::class,
        'client.deny' => \NeubusSrm\Http\Middleware\ClientDeny::class,
        'it.deny' => \NeubusSrm\Http\Middleware\ItDeny::class,
        'client.admin.project' => \NeubusSrm\Http\Middleware\ClientAdminProject::class,
        'mfa.verify' => \NeubusSrm\Http\Middleware\RedirectToVerify::class
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces the listed middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [

        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \NeubusSrm\Http\Middleware\RedirectToVerify::class,
        \NeubusSrm\Http\Middleware\Authenticate::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
