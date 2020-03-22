<?php
/**
 * User: mlawson
 * Date: 2018-11-26
 * Time: 08:49
 */

namespace NeubusSrm\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use NeubusSrm\Http\Controllers\Controller;

/**
 * Class AdminController
 * @package NeubusSrm\Http\Controllers\Admin
 */
class AdminController extends Controller
{

    // TODO: too many actions with index in the name, please consolidate



	// TODO: all routes can be bootstrapped in the constructor using view share
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function indexing() {
		return view('admin.indexing');
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function indexes() {
		return view('admin.indexes');
	}

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function users() {
        return view('users');
    }
    
    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function home() {
        return redirect(route('admin.home'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index() {
        return redirect(route('requests'));
	}

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function log() : View {
        return view('admin.log');
    }

}