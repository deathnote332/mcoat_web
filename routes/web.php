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
Auth::routes();

Route::get('/', function () {
    return view('home');
});





//Admin
Route::get('/admin/dashboard', 'AdminController@index');
Route::get('/admin/mcoat', 'Admin\ProductController@mcoatStocksPage');
Route::get('/admin/getproducts', 'Admin\ProductController@getProducts');
