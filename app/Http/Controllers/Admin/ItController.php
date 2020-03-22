<?php
/**
 * User: mlawson
 * Date: 11/5/18
 * Time: 12:01 PM
 */

namespace NeubusSrm\Http\Controllers\Admin;


use NeubusSrm\Http\Controllers\Controller;
use NeubusSrm\Services\SettingService;

/**
 * Class ItController
 * @package NeubusSrm\Http\Controllers\Admin
 */
class ItController extends Controller
{
    /**
     * @var SettingService
     */
    protected $settingService;

    /**
     * ItController constructor.
     * @param SettingService $service
     */
    public function __construct(SettingService $service){
        $this->settingService = $service;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index() {
        return redirect(route('admin.users'));
    }

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
    public function projects() {
        return view('projects');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function companies() {
        return view('companies');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function settings(){
        return view('settings', ['pageTitle' => 'Settings']);
    }
}