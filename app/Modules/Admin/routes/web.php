<?php

Route::get('admin/dashboard', 'AdminController@dashboard');


Route::get('admin/login', 'Auth\LoginController@showLoginForm')->name('admin.login');

Route::post('admin/login', 'Auth\LoginController@login');

/*Route::get('admin/users', 'UserController@getUser');
*/
Route::resource('admin/user','UserController');

/*Route::post('admin/logout', 'Auth\LoginController@logout');

//Auth::routes();
Route::get('admin/register', 'Admin\Auth\RegisterController@showRegistrationForm');

Route::post('admin/register', 'Auth\RegisterController@register');
*/




