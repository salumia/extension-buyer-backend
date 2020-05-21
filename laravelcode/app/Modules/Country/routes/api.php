<?php

Route::group([ 'prefix' => 'api'], function()
{
    Route::get('getcountries', 'CountryController@getAllCountry');
    
});