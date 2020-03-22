<?php
/**
 * Created by PhpStorm.
 * User: mlawson
 * Date: 2019-03-13
 * Time: 13:03
 */

namespace NeubusSrm\Http\Controllers\Indexing;

use NeubusSrm\Http\Controllers\Controller;

/**
 * Class BoxController
 * @package NeubusSrm\Http\Controllers\Admin
 */
class BoxController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index() {
        // currently no box home, so this will have to do?
        return redirect(route('todo.home'));
    }

    /**
     * @return string
     */
    public function updatelocation() {
        return view('indexing.updatelocation');
    }
}
