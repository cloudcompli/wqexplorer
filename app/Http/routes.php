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

Route::get('ctr', 'CtrController@index');
Route::get('ctr/{programCode}/{parameterCode}/{waterType}/{fraction}', 'CtrController@results');
Route::get('ctr/{programCode}/{parameterCode}/{waterType}/{fraction}/{date}', 'CtrController@investigate');

Route::get('variances/ocpw', 'Variances\OcpwController@index');
Route::get('variances/ocpw/{program}', 'Variances\OcpwController@listParameterTypes');
Route::get('variances/ocpw/{program}/{parameter}/{type}', 'Variances\OcpwController@inspectParameterType');
Route::get('variances/investigations/{parameter}/{type}/{date}', 'Variances\InvestigationsController@overview');

Route::get('/paper', function () {
    return redirect('https://docs.google.com/document/d/1iegjiHFQO7AEqvP6mapbEKgLEmszWQ-Mg9Z2dET9ZMo/edit?usp=sharing');
});

Route::get('/', function () {
    return view('home');
});
