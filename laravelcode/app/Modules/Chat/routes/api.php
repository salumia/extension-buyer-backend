<?php

Route::group([ 'prefix' => 'api'], function()
{
    Route::post('store/chat', 'ChatController@StoreChat');

    Route::post('get/chat', 'ChatController@ChatDetails');
    
});