<?php

Route::get('admin/dashboard', 'AdminController@dashboard');

Route::get('admin/profile','AdminController@adminProfile');
Route::get('admin/editProfile/{id}', 'AdminController@editView');
Route::post('admin/update/{id}','AdminController@updateProfile');
Route::get('admin/changePassword','AdminController@changePasswordView');
Route::post('admin/changepassword/{id}','AdminController@changePasswordStore');

Route::get('/adminq',function()
{
	 return redirect('admin/login');
});

// Route::get('admin', 'Auth\LoginController@showLoginFormRedirect');

Route::get('admin/login', 'Auth\LoginController@showLoginForm')->name('admin.login');

Route::post('admin/login', 'Auth\LoginController@login');

Route::resource('admin/user','UserController');

Route::resource('admin/categories','CategoryController');

/*Route::post('admin/logout', 'Auth\LoginController@logout');

//Auth::routes();
Route::get('admin/register', 'Admin\Auth\RegisterController@showRegistrationForm');

Route::post('admin/register', 'Auth\RegisterController@register');
*/




