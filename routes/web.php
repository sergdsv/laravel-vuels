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


Route::get('/', 'HomeController@index');
Route::get('/post/{slug}', 'HomeController@show')->name('post.show');
Route::get('/tag/{slug}', 'HomeController@tag')->name('post.tag');
Route::get('/category/{slug}', 'HomeController@category')->name('post.category');
Route::post('/subscribe', 'SubsController@subscribe');
Route::get('/verify/{token}', 'SubsController@verify');


Route::group([
	'middleware' => 'auth'
], function(){
	Route::get('/logout', 'AuthController@logout');
	Route::get('/profile', 'ProfileController@index');
	Route::post('/profile', 'ProfileController@store');
	Route::post('/comment', 'CommentsController@store');

});


Route::group([
	'middleware' => 'guest'
], function(){
	Route::get('/login', 'AuthController@loginForm')->name('login');
	Route::post('/login', 'AuthController@login');
	Route::get('/register', 'AuthController@registerForm');
	Route::post('/register', 'AuthController@register');
});


Route::group(['prefix'=>'admin', 'namespace'=>'Admin', 'middleware'=>'admin'], function(){
	Route::get('/', 'DashboardController@index');
	Route::resource('/categories', 'CategoriesController');
	Route::resource('/tags', 'TagsController');
	Route::resource('/posts', 'PostsController');
	Route::resource('/users', 'UsersController');
	Route::get('/comments', 'CommentsController@index');
	Route::get('/comments/toggle/{id}', 'CommentsController@toggle');
	Route::delete('/comments/{comment}', 'CommentsController@destroy')->name('comments.destroy');
	Route::resource('/subscribers', 'SubscribersController');

});
