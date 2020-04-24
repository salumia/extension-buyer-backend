<?php

Route::group([ 'prefix' => 'api'], function()
{
    Route::get('getcity/{id}', 'CityController@getCity');
    
});