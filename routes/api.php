<?php

use Illuminate\Http\Request;

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


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/categories')->group(function () {

    Route::get('/', 'CategoryController@get');
    Route::get('/{id}', 'CategoryController@getProductsFrom');

    Route::middleware('auth:api')->group(function () {
        Route::post('/create', 'CategoryController@create');
        Route::post('/update/{id}', 'CategoryController@update');
        Route::post('/delete/{id}', 'CategoryController@delete');

    });
});

Route::prefix('/products')->group(function () {

    Route::middleware('auth:api')->group(function () {

        Route::post('/create', 'ProductController@create');
        Route::post('/update/{id}', 'ProductController@update');
        Route::post('/delete/{id}', 'ProductController@delete');
    });

});
