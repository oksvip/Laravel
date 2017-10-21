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

// Auth::routes();
// Route::get('/home', 'HomeController@index');
// Route::get('/mail', 'MailController@mail');

Route::get('/', 'StaticPagesController@home')->name('home');            // 首页
Route::get('/help', 'StaticPagesController@help')->name('help');        // 帮助页
Route::get('/about', 'StaticPagesController@about')->name('about');     // 关于页
Route::get('/signup', 'UsersController@create')->name('signup');        // 注册
Route::resource('users', 'UsersController');                            // 用户
Route::get('login', 'SessionsController@create')->name('login');        // 登录
Route::post('login', 'SessionsController@store')->name('login');        // 登录操作
Route::delete('logout', 'SessionsController@destroy')->name('logout');   // 注销
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');   // 激活链接
