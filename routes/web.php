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
Route::post('login', 'Auth\LoginController@authenticate')->name('login');

Route::get('/', 'AdminController@dashboard');





//Admin
Route::get('/admin/dashboard', 'AdminController@dashboard');
Route::get('/admin/products', 'AdminController@products');
Route::get('/admin/manage-products', 'AdminController@manageProducts');
Route::get('/admin/product-out', 'AdminController@productOut');
Route::get('/admin/product-in', 'AdminController@productIn');
Route::get('/admin/receipts', 'AdminController@receipts');
Route::get('/admin/receipts-in', 'AdminController@receiptsIn');
Route::get('/edit-receipt', 'AdminController@editReceipt');

//products
Route::get('/getproducts', 'ProductController@getProducts');
Route::post('/add-product', 'ProductController@addProduct');
Route::post('/update-product', 'ProductController@updateProduct');
Route::post('/delete-product', 'ProductController@deleteProduct');
Route::get('/product-cart', 'ProductController@productCart');
Route::post('/add-cart', 'ProductController@addToCart');
Route::post('/remove-cart', 'ProductController@removeToCart');
Route::post('/print-cart', 'ProductController@printCart');

Route::post('/save-products', 'ProductController@saveProducts');



//sales report
Route::get('/sales-report', 'ReportController@getSalesReport');
Route::get('/invoice', 'ReportController@invoice');

//receipts
Route::post('/get-receipts', 'ReportController@getReciepts');
Route::post('/get-receipts-in', 'ReportController@getRecieptsIn');