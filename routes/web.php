<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'IndexController@index');
Route::post('/Search/query', 'SearchController@query');
Route::post('/Search/find', 'SearchController@find');
Route::post('/Search/state', 'SearchController@state');
Route::get('/Search/result/{query?}/{page?}', 'SearchController@result');
Route::get('/Search/filter/{query?}/{filter?}/{page?}', 'SearchController@filter');

Route::get('/Image/Edit/{image?}', 'ImageController@edit');
Route::post('/Image/Save/', 'ImageController@save');
Route::post('/Image/Update/', 'ImageController@update');
Route::get('/api/{image?}', 'ImageController@index');