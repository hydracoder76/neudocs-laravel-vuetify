<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// the middleware will be enabled when login is finished, for now it's unprotected
//Route::group(['middleware' => 'auth'], function() {
Route::group(['prefix' => 'it', 'namespace' => 'Admin', 'middleware' => ['auth', 'it.check', 'reset.password', 'mfa.verify']], function () {
    Route::get('', ['as' => 'it.home', 'uses' => 'ItController@index']);
    Route::get('projects', ['as' => 'it.projects', 'uses' => 'ItController@projects']);
    Route::get('companies', ['as' => 'it.companies', 'uses' => 'ItController@companies']);
    Route::get('settings', ['as' => 'it.settings', 'uses' => 'ItController@settings']);
    Route::get('updatelocation', ['as' => 'neubus.updatelocation', 'uses' => 'NeubusController@updatelocation']);
    Route::permanentRedirect('updateprofile', 'user.updateprofile');


});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin.check', 'reset.password', 'mfa.verify']], function () {
    Route::get('users', ['as' => 'admin.users', 'uses' => 'AdminController@users']);
    Route::get('', ['as' => 'admin.home', 'uses' => 'AdminController@index']);
    Route::get('indexing', ['as' => 'admin.indexing', 'uses' => 'AdminController@indexes'])->middleware(['admin.deny']);
    Route::get('log', ['as' => 'admin.log', 'uses' => 'AdminController@log'])->middleware(['neubus.deny']);
    Route::permanentRedirect('', 'requests');
    Route::permanentRedirect('updateprofile', 'user.updateprofile');
});

Route::group(['prefix' => 'client', 'namespace' => 'Client', 'middleware' => ['auth', 'client.check', 'reset.password', 'mfa.verify']], function () {
    Route::get('', ['as' => 'client.home', 'uses' => 'ClientController@index']);
    Route::get('users', ['as' => 'client.users', 'uses' => 'ClientController@users']);
    Route::permanentRedirect('', 'requests');
    Route::permanentRedirect('updateprofile', 'user.updateprofile');
});

Route::group(['prefix' => 'box', 'namespace' => 'Indexing', 'middleware' => ['auth', 'neubus.check', 'mfa.verify']], function () {
    Route::get('', ['as' => 'box.home', 'uses' => 'BoxController@index']);
    Route::get('updatelocation', ['as' => 'box.updatelocation', 'uses' => 'BoxController@updatelocation']);
    Route::permanentRedirect('updateprofile', 'user.updateprofile');
});

Route::group(['prefix' => 'client', 'namespace' => 'Client', 'middleware' => ['auth', 'client.check', 'reset.password', 'mfa.verify']],
    function () {
        Route::permanentRedirect('', 'requests');
    });

Route::group(['prefix' => 'requests', 'namespace' => 'Requests', 'middleware' => ['auth', 'client.check', 'reset.password', 'mfa.verify']],
    function () {
        Route::group(['prefix' => 'todo', 'middleware' => ['neubus.check']], function () {
            Route::get('', ['as' => 'todo.home', 'uses' => 'TodoController@index']); //must come first, else project
            Route::get('upload',
                ['as' => 'todo.upload', 'uses' => 'TodoController@viewUpload']); //must come first, else project
            // will be scanned for during route request
            Route::get('scan', ['as' => 'todo.scan', 'uses' => 'TodoController@viewScan']);
            Route::get('completed', ['as' => 'todo.completed', 'uses' => 'TodoController@viewCompleted']);
        });
        Route::group(['prefix' => 'dataentry', 'middleware' => ['neubus.check']], function () {
            Route::get('', ['as' => 'dataentry.home', 'uses' => 'DataEntryController@index']);
            Route::get('box', ['as' => 'dataentry.box', 'uses' => 'DataEntryController@addBox']);
            Route::get('part', ['as' => 'dataentry.part', 'uses' => 'DataEntryController@addPart']);

        });
        Route::get('', ['as' => 'requests', 'uses' => 'RequestController@index'])->middleware(['neubus.deny']);
        Route::get('new/{project}', ['as' => 'requests.new', 'uses' => 'RequestController@newRequest'])->middleware(['client.admin.project']);
        Route::get('review', ['as' => 'requests.review', 'uses' => 'RequestController@review'])->middleware(['neubus.check']);

    });

// only allows us to get here if a user is logged in and their session expires
Route::get('auth/verify', ['as' => 'auth.verify', 'uses' => 'Auth\MfaController@mfaRelogin'])
    ->middleware(['auth']);


Route::group(['namespace' => 'Auth'], function () {
    Route::permanentRedirect('', 'login');
    Route::get('login', ['as' => 'login.form.view', 'uses' => 'LoginController@index']);
    Route::get('logout', ['as' => 'logout', 'uses' => 'LoginController@logout']);
    Route::get('resetPassword', ['as' => 'reset.password', 'uses' => 'ResetPasswordController@resetPassword'])->middleware(['auth', 'show.reset.password']);

});

Route::group([
    'prefix' => 'profile',
    'namespace' => 'Profile',
    'middleware' => ['auth', 'client.check', 'mfa.verify']
], function () {
    Route::get('update', ['as' => 'user.updateprofile', 'uses' => 'UserProfileController@index']);
});
