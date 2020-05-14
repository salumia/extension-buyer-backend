<?php


Route::group([ 'prefix' => 'api'], function()
{
    Route::get('getCategoriesAndProductType', 'ProductController@getCategoriesAndProductType');//->middleware('auth:api');


    Route::post('product/create', 'ProductController@createProduct');//->middleware('auth:api');

    Route::post('product/uploadimage/{type}', 'ProductController@uploadProductMedia');//->middleware('auth:api');

    Route::post('product/saveimages', 'ProductController@saveProductImage');
    
    Route::get('product/listing', 'ProductController@productListing');
    
    Route::get('product/view/{id}', 'ProductController@productView');
    
    Route::get('getProductRawDetails/{id}', 'ProductController@getProductRawDetails');
    
    Route::post('updateProductRawDetails/{id}', 'ProductController@updateProductRawDetails');
    
    Route::get('product/delete/{id}', 'ProductController@productDelete');
    
    Route::post('product/submitoffer', 'ProductController@submitOffer');

    Route::post('product/sendbuyermessage', 'ProductController@saveBuyerMessage');
    
    Route::get('product/getall', 'ProductController@getAllProducts');
    
    Route::get('product/getProductTypeListings', 'ProductController@getProductTypeListings');

    Route::get('product/getLatestListings', 'ProductController@getLatestListings');

    
    
});
 