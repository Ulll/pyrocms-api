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
Route::get('/', function (Request $request) {
    return 'welcome to pyrocms api service';
});

Route::get('post/list', 'Entry\PostController@list')->name('postlist');

Route::get('post/{id}', 'Entry\PostController@post')->name('postdetail');
