<?php

use Illuminate\Http\Request;
// routes/api.php
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


Route::get('/greeting', function (Request $request){
    return 'Hello World! AG12';
});

// TAREA 
// NOTA * PUERTO LOCAL 192.168.100.26

// CREATE product
Route::POST('products',"ProductController@store");

// LIST ALL productos
Route::GET('products', "ProductController@index");

// SHOW Product 
Route::GET('products/{id}', "ProductController@show");
// Update Product 
Route::PUT('products/{id}', "ProductController@update");

// DELETE Product
Route::DELETE('products/{id}',"ProductController@destroy");


