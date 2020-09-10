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
    return redirect(route('home'));
})->middleware('auth');

Auth::routes();

Route::get('auth/google', 'Auth\GoogleController@redirectToGoogle');
Route::get('auth/google/callback', 'Auth\GoogleController@handleGoogleCallback');

Route::any('/home', 'HomeController@index')->name('home');
Route::any('/projects/hidden', 'HomeController@hidden_projects')->name('hidden_projects');
Route::post('/project/update', 'HomeController@project_update')->name('project.update');

Route::get('/user/index', 'UserController@index')->name('user.index');
Route::post('/user/create', 'UserController@create')->name('user.create');
Route::get('/user/delete/{id}', 'UserController@destroy')->name('user.delete');
Route::post('/user/change_password', 'UserController@change_password')->name('user.change_password');

Route::get('/owner/index', 'OwnerController@index')->name('owner.index');
Route::post('/owner/create', 'OwnerController@create')->name('owner.create');
Route::get('/owner/delete/{id}', 'OwnerController@destroy')->name('owner.delete');

Route::get('/setting/index', 'HomeController@setting')->name('setting.index');
Route::post('/setting/update', 'HomeController@setting_update')->name('setting.update');





Route::get('/get_billable', 'HomeController@getBillable');
