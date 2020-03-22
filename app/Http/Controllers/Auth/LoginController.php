<?php

namespace NeubusSrm\Http\Controllers\Auth;

use Illuminate\Http\Request;
use NeubusSrm\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use NeubusSrm\Http\Requests\NeuSrmVerifyRequest;
use NeubusSrm\Lib\Constants\HttpConstants;
use NeubusSrm\Services\UserService;

/**
 * Class LoginController
 * @package NeubusSrm\Http\Controllers\Auth
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

	use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/login';

	/**
	 * @var UserService
	 */
    private $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService) {
        $this->middleware('guest')->except('logout');
        $this->userService = $userService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        return view('login');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logout() {
        $this->userService->logoutUser();
        return view('login');
    }

}
