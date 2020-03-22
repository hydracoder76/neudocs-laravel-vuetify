<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'api\v1', 'prefix' => 'v1'], function() {
	Route::group(['namespace' => 'Auth'], function() {
		Route::post('login', ['as' => 'login.do', 'uses' => 'AuthApiController@loginViaApi']);
		Route::post('verify', ['as' => 'login.do.verify', 'uses' => 'AuthApiController@verifyLogin']);
		Route::post('token', ['as' => 'login.do.token', 'uses' => 'AuthApiController@finishLoginWithOtp']);
		Route::get('mfa', ['as' => 'login.mfa.setup', 'uses' => 'AuthApiController@getMfaQrCode']);
		Route::post('mfa', ['as' => 'login.mfa.verify', 'uses' => 'AuthApiController@verifyMfaSetup']);
		Route::delete('mfa', ['as' => 'login.mfa.cancel', 'uses' => 'AuthApiController@cancelMfaSetup']);
		Route::post('refresh', ['as' => 'login.do.refresh', 'uses' => 'AuthApiController@refreshMfaSession']);
	});

	Route::group(['namespace' => 'General', 'prefix' => 'g', 'middleware' => ['mfa.verify']], function() {
	    Route::post('search', ['as' => 'do.search', 'FilterApiController@search']);
        Route::post('upload', ['as' => 'do.upload', 'uses' => 'FileApiController@upload']);
        Route::post('upload/remove', ['as' => 'do.remove.upload', 'uses' => 'FileApiController@removeUpload']);
        Route::post('settings', ['as' => 'do.settings.save', 'uses' => 'ItApiController@saveSetting']);
        Route::get('settings/project', ['as' => 'do.settings.get', 'uses' => 'ItApiController@getSettingByProject']);
        Route::get('download', ['as' => 'requests.download', 'uses' => 'FileApiController@downloadZipFromRequest']);
        Route::get('download/part', ['as' => 'requests.download.part', 'uses' => 'FileApiController@downloadZipFromPart']);
        Route::get('download/box', ['as' => 'requests.download.box', 'uses' => 'FileApiController@downloadZipById']);
        Route::get('log/search', ['as' => 'log.search', 'uses' => 'LogApiController@search']);
    });

	Route::group(['namespace' => 'Requests', 'prefix' => 'request', 'middleware' => ['mfa.verify']], function(){
        Route::post('project/{project?}', ['as' => 'requests.project', 'uses' => 'TodoApiController@requestList']);
        Route::get('filter', ['as' => 'requests.filter', 'uses' => 'RequestApiController@filter']);
        Route::get('search', ['as' => 'requests.search', 'uses' => 'RequestApiController@search']);
        Route::post('newRequest', ['as' => 'requests.newRequest', 'uses' => 'RequestApiController@newRequest']);
        Route::get('auto/part', ['as' => 'requests.auto.part', 'uses' => 'RequestApiController@autoPartSearch']);
        Route::post('review', ['as' => 'requests.review.send', 'uses' => 'RequestApiController@review']);
        Route::group(['prefix' => 'todo'], function() {
            Route::post('nav', ['as' => 'request.todo.nav', 'uses' => 'TodoApiController@navigate']);
            Route::post('select', ['as' => 'request.todo.select', 'uses' => 'TodoApiController@selectForFulfillment']);
            Route::get('completed/list', ['as' => 'request.todo.completed.list', 'uses' => 'TodoApiController@completedList']);
            Route::get('completed/filter', ['as' => 'request.todo.completed.filter', 'uses' => 'TodoApiController@completedFilter']);
            Route::post('unlock', ['as' => 'request.todo.unlock', 'uses' => 'TodoApiController@unlock']);
            Route::get('get/search', ['as' => 'todo.search', 'uses' => 'TodoApiController@todoSearch']);
            Route::post('fulfill', ['as' => 'request.todo.fulfill', 'uses' => 'TodoApiController@fulfillParts']);
            Route::post('part/media/type/all', ['as' => 'part.media.type.all', 'uses' => 'TodoApiController@getRequestPartMediatype']);
        });
        Route::group(['prefix' => 'dataentry'], function() {
            Route::get('all/{format?}', ['as' => 'request.dataentry.all', 'uses' => 'DataEntryApiController@all']);
            Route::get('boxes/get', ['as' => 'request.dataentry.box.project', 'uses' => 'DataEntryApiController@projectBox']);
     	    Route::get('partindexes/get', ['as' => 'request.dataentry.partindex.box', 'uses' => 'DataEntryApiController@boxPartIndex']);
            Route::post('partindexes/schema/{project}', ['as' => 'request.dataentry.partindex.box.schema', 'uses' => 'DataEntryApiController@boxPartIndexSchemaOnly']);
            Route::post('search', ['as' => 'request.dataentry.search', 'uses' => 'DataEntryApiController@search']);
            Route::post('boxes', ['as' => 'request.dataentry.box.new', 'uses' => 'DataEntryApiController@addNewBox']);
            Route::post('parts', ['as' => 'request.dataentry.part.new', 'uses' => 'DataEntryApiController@addNewPart']);
            Route::post('partindexes', ['as' => 'request.dataentry.partindex.new', 'uses' => 'DataEntryApiController@addNewPartIndex']);
        });
        Route::group(['prefix' => 'boxtypedef'], function() {
            Route::get('all', ['as' => 'request.boxtypedef.all', 'uses' => 'BoxTypeDefApiController@all']);
        });
    });

	Route::group(['namespace' => 'Projects', 'middleware' => ['mfa.verify']], function() {
        Route::apiResource('projects','ProjectsApiController');
        Route::group([ 'prefix' => 'project'], function() {
            Route::get('all/{format?}', ['as' => 'projects.all', 'uses' => 'ProjectsApiController@all']);
            Route::get('user/default', ['as' => 'user.default.project', 'uses' => 'ProjectsApiController@getUserDefaultProject']);
            Route::get('get/sort', ['as' => 'projects.sort', 'uses' => 'ProjectsApiController@projectSort']);
        });
	});

	Route::group(['namespace' => 'Indexing', 'prefix' => 'indexing', 'middleware' => ['mfa.verify']], function() {
		Route::get('indexes/{projectId}', ['as' => 'indexes.project.get', 'uses' => 'IndexesApiController@byProjectId']);
		Route::get('indexes', ['as' => 'indexes.project.get.all', 'uses' => 'IndexesApiController@all']);
		Route::post('indexes', ['as' => 'indexes.project.new', 'uses' => 'IndexesApiController@addNewIndex']);
		Route::post('indexes/delete', ['as' => 'indexes.project.delete', 'uses' => 'IndexesApiController@deleteIndex']);
        Route::post('indexes/edit', ['as' => 'indexes.project.edit', 'uses' => 'IndexesApiController@editIndex']);
	});

    Route::group(['namespace' => 'User', 'middleware' => ['mfa.verify']], function(){
        Route::group([ 'prefix' => 'user'], function() {
            Route::get('all/{format?}', ['as' => 'user.all', 'uses' => 'UserApiController@all']);
            /**
             * @deprecated
             */
            Route::post('search', ['as' => 'user.search', 'uses' => 'UserApiController@search']);
            Route::get('byCompany/{companyId}', ['as' => 'user.get.bycompany', 'uses' => 'UserApiController@byCompanyId']);
            Route::post('updateDefaultProject', ['as' => 'user.update.default.project', 'uses' => 'UserApiController@updateDefaultProject']);
            Route::put('profile', ['as' => 'user.update.profile', 'uses' => 'UserApiController@updateProfile']);
        });
        Route::apiResource('user','UserApiController');
        Route::post('resetPasswordDouble', ['as' => 'resetpassword.double', 'uses' => 'UserApiController@resetPasswordDouble']);
        Route::put('resetPasswordUser', ['as' => 'resetpassword.user', 'uses' => 'UserApiController@resetPasswordUser']);
        Route::get('get/search', ['as' => 'user.search', 'uses' => 'UserApiController@userSearch']);
    });

    Route::group(['namespace' => 'Company', 'middleware' => ['mfa.verify']], function(){
        Route::group([ 'prefix' => 'company'], function() {
            Route::get('all/{format?}', ['as' => 'company.all', 'uses' => 'CompaniesApiController@all']);
            Route::get('get/search', ['as' => 'company.search', 'uses' => 'CompaniesApiController@companySearch']);
        });
        Route::apiResource('company','CompaniesApiController');

    });

    Route::group(['namespace' => 'Indexing', 'prefix' => 'box', 'middleware' => ['mfa.verify']], function() {
        Route::post('location', ['as' => 'box.location', 'uses' => 'BoxApiController@addNewLocation']);
    });


    Route::group(['namespace' => 'MediaType', 'middleware' => ['mfa.verify']], function(){
        Route::group([ 'prefix' => 'media-type'], function() {
            Route::get('all/{format?}', ['as' => 'media.type.all', 'uses' => 'MediaTypeApiController@all']);
        });
    });
});
