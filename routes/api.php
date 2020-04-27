<?php

use Illuminate\Http\Request;

Route::group([

    'middleware' => 'api'  
    // 'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::get('logout', 'AuthController@logout');
    Route::get('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');
    Route::post('registration', 'AuthController@registration');
    
    Route::post('password/create', 'PasswordResetController@create');
    Route::get('password/find/{token}', 'PasswordResetController@find');
    Route::post('password/reset', 'PasswordResetController@reset');
});

Route::POST('validateUserEmailAdd','API\UserController@validateUserEmailAdd');   
//Route::POST('validateuseremailedit/{$id}','API\UserController@validateuseremailedit');   




 