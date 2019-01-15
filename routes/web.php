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

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();
Route::get('logout', 'LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/profile', 'ProfileController@index')->name('profile');
Route::post('edit', 'ProfileController@edit')->name('edit');
Route::post('/uploadShot', 'shotController@uploadShot')->name('uploadShot');
Route::post('/editShot', 'shotController@editShot')->name('editShot');

Route::post('create', 'HomeController@create')->name('create');
Route::get('/post{id}/delete', 'HomeController@destroy')->name('delete');
Route::post('/post{id}/edit', 'HomeController@edit')->name('edit');