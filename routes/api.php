<?php

use Illuminate\Http\Request;

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});


  Route::post('registration', 'AuthController@registration');
  
  
  ///////////product api/// /home/rtcsof5d8edw/public_html/sell-admin/app/Modules/Client/Http/Controllers/ClientController.php
//   Route::group([

//     'namespace' => 'App\Modules\ClientController'

// ], function ($router) {

//     Route::get('product', 'Modules\Client\Http\Controllers\ClientController@test'); 


// });



  
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
