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

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
Route::post('create', 'HomeController@create')->name('create');
Route::post('ajax/delete', 'Ajax\DeleteController@destroy');
Route::post('/post{id}/edit', 'HomeController@edit')->name('editPost');

Route::get('/profile', 'ProfileController@index')->name('profile');
Route::post('edit', 'ProfileController@edit')->name('edit');
Route::prefix('/Shot')->group(function(){
	Route::post('/post', 'shotController@uploadShot')->name('uploadShot');
	Route::post('/put', 'shotController@editShot')->name('editShot');
});

Route::prefix('ajax')->group(function(){
	Route::post('/addFriend', 'Ajax\FriendController@add');
	Route::post('/agreeAddFriend', 'Ajax\FriendController@agree');
	Route::post('/rejectAddFriend', 'Ajax\FriendController@reject');
});

Route::get('/stories/{whoYouAre}', 'StoryController@index')->name('stories');
Route::get('ajax/autocomplete', 'Ajax\FriendController@autocomplete')->name('autocomplete');
Route::post('/search', 'SearchController@search')->name('search');

Route::prefix('ajax')->group(function(){
	Route::post('/like', 'Ajax\LikeController@like');
	Route::post('/unlike', 'Ajax\LikeController@unlike');
	Route::post('/whoLikes', 'Ajax\LikeController@whoLikes');
});

Route::post('/comment/post{id}', 'CommentController@create');
Route::post('ajax/comment/get', 'CommentController@allComments');

Route::get('/auth/redirect/{provider}', 'Auth\LoginController@redirect');
Route::get('/callback/{provider}', 'Auth\LoginController@callback');

/*Route::get('error', function(){
	abort(500);
});*/