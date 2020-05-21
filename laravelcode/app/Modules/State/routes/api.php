<?php

Route::group([ 'prefix' => 'api'], function()
{
    Route::get('getstate/{id}', 'StateController@getState');
    
});