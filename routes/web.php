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

Route::get('/', 'MastodonLoginController@index');
Route::post('/login', 'MastodonLoginController@login');
Route::post('/logout', 'MastodonLoginController@logout');
Route::get('/login/callback', 'MastodonLoginController@callback');
Route::get('/following', 'FollowingController@index');