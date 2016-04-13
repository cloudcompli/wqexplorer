<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('ocpw/{program}', 'OcpwController@listParameterTypes');
Route::get('ocpw/{program}/{parameter}/{type}', 'OcpwController@inspectParameterType');
Route::get('investigations/{parameter}/{type}/{date}', 'InvestigationsController@overview');

Route::get('/', function () {
    return view('layout');
});
