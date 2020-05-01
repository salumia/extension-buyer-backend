<?php


Route::group([ 'prefix' => 'api'], function()
{
    Route::get('getCategoriesAndProductType', 'ProductController@getCategoriesAndProductType');//->middleware('auth:api');


    Route::post('product/create', 'ProductController@createProduct');//->middleware('auth:api');

    Route::post('product/uploadimage/{type}', 'ProductController@uploadProductMedia');//->middleware('auth:api');

    Route::post('product/saveimages', 'ProductController@saveProductImage');

     Route::get('product/listing', 'ProductController@productListing');
});