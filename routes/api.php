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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function() {
	// jwt
	Route::post('register', 'ApiController@register');
	Route::post('login', 'ApiController@authenticate');
	Route::get('open', 'ApiController@open');
	Route::get('logout', 'ApiController@logout');
	
	Route::group(['middleware' => ['jwt.verify']], function() {
		Route::get('user', 'ApiController@getAuthenticatedUser');
	    Route::get('closed', 'ApiController@closed');
		Route::get('events', 'EventController@index');
		Route::post('events/create', 'EventController@create');
	});
});


