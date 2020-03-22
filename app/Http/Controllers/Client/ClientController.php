<?php
/**
 * User: mlawson
 * Date: 11/5/18
 * Time: 12:04 PM
 */

namespace NeubusSrm\Http\Controllers\Client;


use NeubusSrm\Http\Controllers\Controller;

/**
 * Class ClientController
 * @package NeubusSrm\Http\Controllers\Client
 */
class ClientController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index() {
        return redirect('/requests');
    }

}