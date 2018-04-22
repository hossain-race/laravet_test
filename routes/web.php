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

Route::get('/', function () {
    return view('welcome');
});


// Admin Interface Routes
Route::group(['prefix' => 'admin', 'middleware' => 'admin', 'namespace' => 'Admin'], function () {
    // Backpack\CRUD: Define the resources for the entities you want to CRUD.
    CRUD::resource('product', 'ProductCrudController');
    CRUD::resource('hijackercheck', 'ProductHijackCrudController');
//    CRUD::resource('complain', 'ComplainCrudController');
//    CRUD::resource('customer', 'UserCrudController');
//    CRUD::resource('log', 'LogCrudController');

});
Route::get('refreshhijackercheck', 'HijackerController@refreshHijackerCheck');
Route::get('product/addall', 'HijackerController@addAllProduct');
Route::get('product/add', 'HijackerController@addProduct');
Route::post('product/save', 'HijackerController@saveProduct');
Route::post('admin/product/delete/{id}', 'HijackerController@deleteProduct');
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');