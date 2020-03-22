<?php
/**
 * Created by PhpStorm.
 * User: mlawson
 * Date: 2019-03-08
 * Time: 18:17
 */

namespace NeubusSrm\Http\Controllers\Auth;


use NeubusSrm\Http\Controllers\Controller;
use NeubusSrm\Rules\VerifyOtp;

/**
 * Class MfaController
 * @package NeubusSrm\Http\Controllers\Auth
 */
class MfaController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mfaRelogin() {
        return view ('security', ['currentAttempts' => session(VerifyOtp::CURRENT_ATTEMPT_KEY, 0),
            'maxAttempts' => config('srm.max_mfa_refresh_attempt')]);
    }
}