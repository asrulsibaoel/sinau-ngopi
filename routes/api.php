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

Route::group(['middleware' => ['auth:api']], function () {

    Route::get('/account/profile', 'UserController@profile');
    Route::post('/account/edit_profile', 'UserController@edit_profile');
/*------------------------------------
	CRUD Category
------------------------------------*/
    Route::get('/category', 'CategoryController@data');
    Route::get('/category/{id}', 'CategoryController@detail');
    Route::post('/category/create', 'CategoryController@create');
    Route::post('/category/update', 'CategoryController@update');
    Route::get('/category/delete/{id}', 'CategoryController@delete');
    Route::get('/category/force_delete/{id}', 'CategoryController@force_delete');

/*------------------------------------
	CRUD Product
------------------------------------*/
    Route::get('/product', 'ProductController@data');
    Route::get('/product/self', 'ProductController@my_product');
    Route::get('/product/{id}', 'ProductController@detail');
    Route::post('/product/create', 'ProductController@create');
    Route::post('/product/update', 'ProductController@update');
    Route::get('/product/delete/{id}', 'ProductController@delete');
    Route::get('/product/force_delete/{id}', 'ProductController@force_delete');
/*------------------------------------
	CRUD Chats Room
------------------------------------*/
    Route::get('/chats_room', 'ChatsRoomController@data');
    Route::get('/chats_room/{id}', 'ChatsRoomController@detail');
    Route::post('/chats_room/create', 'ChatsRoomController@create');
    Route::post('/chats_room/update', 'ChatsRoomController@update');
    Route::get('/chats_room/delete/{id}', 'ChatsRoomController@delete');
    Route::get('/chats_room/force_delete/{id}', 'ChatsRoomController@force_delete');

/*------------------------------------
	CRUD Chat
------------------------------------*/
    Route::get('/chat', 'ChatController@data');
    Route::get('/chat/{id}', 'ChatController@detail');
    Route::post('/chat/create', 'ChatController@create');
    Route::post('/chat/update', 'ChatController@update');
    Route::get('/chat/delete/{id}', 'ChatController@delete');
    Route::get('/chat/force_delete/{id}', 'ChatController@force_delete');

/*------------------------------------
	CRUD Comment
------------------------------------*/
    Route::get('/comment', 'CommentController@data');
    Route::get('/comment/{id}', 'CommentController@detail');
    Route::post('/comment/create', 'CommentController@create');
    Route::post('/comment/update', 'CommentController@update');
    Route::get('/comment/delete/{id}', 'CommentController@delete');
    Route::get('/comment/force_delete/{id}', 'CommentController@force_delete');

/*------------------------------------
	CRUD Transaction
------------------------------------*/
    Route::get('/transaction', 'TransactionController@data');
    Route::get('/transaction/detail/{id}', 'TransactionsDetailController@data');
    Route::get('/transaction/{id}', 'TransactionController@detail');
    Route::post('/transaction/create', 'TransactionController@create');
    // Route::post('/transaction/update', 'TransactionController@update');
    Route::get('/transaction/delete/{id}', 'TransactionController@delete');
    Route::get('/transaction/force_delete/{id}', 'TransactionController@force_delete');

/*------------------------------------
	CRUD Transactions Detail
------------------------------------*/
    Route::get('/transactions_detail', 'TransactionsDetailController@data');
    Route::get('/transactions_detail/{id}', 'TransactionsDetailController@detail');
    Route::post('/transactions_detail/create', 'TransactionsDetailController@create');
    // Route::post('/transactions_detail/update', 'TransactionsDetailController@update');
    Route::get('/transactions_detail/delete/{id}', 'TransactionsDetailController@delete');
    Route::get('/transactions_detail/force_delete/{id}', 'TransactionsDetailController@force_delete');


    Route::post('/image/upload', 'ImageController@upload');
});

