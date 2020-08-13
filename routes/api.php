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


Route::middleware(['auth:api'])->group(function () {
    Route::GET('/user', 'AuthController@getUser');
    Route::GET('/logout', 'AuthController@logout');
    

});

Route::POST('/login','AuthController@login');
Route::POST('/register','AuthController@register');
Route::POST('/prod_images','ProductController@uploadMultipleImages');
Route::get('/getImages', 'ProductController@getImages');
Route::get('/getImage/{img_name}','ProductController@getImage');

// Route::get('/demo1','DemoController@demo');
// Route::get('/getDemoData','DemoController@getData');
// Route::post('/getPostData','DemoController@ostData');
// Route::get('/demo', 'DemoController@demo');