<?php


use Illuminate\Http\Request;

Route::post('api/extensionStatus/{id}', 'ExtensionController@extensionStatus');
Route::POST('api/validateUserEmailCheck','ExtensionController@validateUserEmailCheck');
Route::POST('api/validateAdminEmailCheck','ExtensionController@validateAdminEmailCheck');
Route::post('api/extensionStatusReject/{id}', 'ExtensionController@extensionStatusReject');
