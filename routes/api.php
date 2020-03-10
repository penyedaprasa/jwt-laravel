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

Route::post('login', 'ApiController@postLogin');
Route::post('registrasi', 'ApiController@postRegister');
Route::get('coba', 'ApiController@coba');

Route::group(['prefix' => 'v1'], function() {
	Route::post('nc/add', 'ApiController@ncAdd');

	// jwt
	Route::post('register', 'ApiController@register');
	Route::post('login', 'ApiController@authenticate');
	Route::get('open', 'ApiController@open');
	Route::get('logout', 'ApiController@logout');

	Route::group(['middleware' => ['jwt.verify']], function() {
		Route::get('list/part', 'ApiController@listPart');
	    Route::get('user', 'ApiController@getAuthenticatedUser');
	    Route::get('closed', 'ApiController@closed');
	});
});


