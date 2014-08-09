<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(array('prefix' => 'instance/{id}'), function () {
        Route::get('/start', 'InstanceController@start');
        Route::get('/pause', 'InstanceController@pause');
        Route::get('/resume', 'InstanceController@resume');
        Route::get('/kill', 'InstanceController@kill');
        Route::get('/dump', 'InstanceController@dump');
});

Route::group(array('prefix' => 'flow/{name}'), function () {
    Route::get('/start', 'FlowController@start');
    Route::get('/dump', 'FlowController@dump');
});
