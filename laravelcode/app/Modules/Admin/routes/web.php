<?php

 Route::get('/login', 'Auth\LoginController@showLoginForm')->name('admin.login');
 
  Route::post('/login', 'Auth\LoginController@login')->name('login');
  
    Route::post('/logout', 'Auth\LoginController@logout');

Route::group(['middleware' => 'AdminAuth'], function () {

   // Route::get('/dashboard', 'AdminController@dashboard')->middleware('AdminAuth')->name('dashboard');
    
     Route::get('/dashboard', 'AdminController@dashboard')->name('dashboard');
    Route::get('/profile','AdminController@adminProfile');
    Route::get('/editProfile/{id}', 'AdminController@editView');
    Route::post('/update/{id}','AdminController@updateProfile');
    Route::get('/changePassword','AdminController@changePasswordView');
    Route::post('/changepassword/{id}','AdminController@changePasswordStore');
  
    Route::resource('/user','UserController');
    Route::resource('/categories','CategoryController');
    Route::resource('/extension','ExtensionController');
    
    /*Route::get('admin/users', 'UserController@getUser');
    */
    
 });    


/*Route::post('admin/logout', 'Auth\LoginController@logout');

//Auth::routes();
Route::get('admin/register', 'Admin\Auth\RegisterController@showRegistrationForm');

Route::post('admin/register', 'Auth\RegisterController@register');
*/




