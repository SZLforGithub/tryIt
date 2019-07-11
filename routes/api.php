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

Route::get('post', 'api\PostController@index');
Route::get('post/{post}', 'api\PostController@show');
Route::post('post', 'api\PostController@store');
Route::put('post/{post}', 'api\PostController@update');
Route::delete('post/{post}', 'api\PostController@delete');

Route::apiResource('post', 'api\PostController');

