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
    return view('landingpage');
});

Route::get('/chats', 'ChatController@web_chat');

Route::post('/account/register', 'UserController@register');
Route::post('/account/login', 'UserController@login');


Route::get('/devel/email', 'DevelController@email');