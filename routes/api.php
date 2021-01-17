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
	Route::post('register', 'API\ApiController@register');
	Route::post('login', 'API\ApiController@authenticate');
	Route::get('open', 'API\ApiController@open');
	Route::get('logout', 'API\ApiController@logout');
	
	Route::group(['middleware' => ['jwt.verify']], function() {
		Route::get('user', 'API\ApiController@getAuthenticatedUser');
	    Route::get('closed', 'API\ApiController@closed');
		Route::get('events', 'API\EventController@index');
		Route::post('events/create', 'API\EventController@store');
		Route::post('events/update', 'API\EventController@store');
		Route::post('events/delete/{id}', 'API\EventController@delete');
		Route::get('events/orders', 'API\OrderController@index');
		Route::post('events/order/create', 'API\OrderController@store');
		Route::post('events/order/delete/{code}', 'API\OrderController@delete');
		Route::post('events/order/update', 'API\OrderController@update');
		Route::get('users', 'API\UserController@index');
		Route::post('users/profile/edit/{id}', 'API\UserController@editProfile');
		Route::post('users/create', 'API\UserController@createUser');
	});
});


