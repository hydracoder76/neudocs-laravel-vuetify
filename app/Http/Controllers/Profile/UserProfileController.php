<?php

namespace NeubusSrm\Http\Controllers\Profile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use NeubusSrm\Http\Controllers\Controller;

class UserProfileController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        return view('user.updateprofile', ['user' => Auth::user()]);
    }
}
