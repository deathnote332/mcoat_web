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
Route::get('/admin/stock-report', 'AdminController@stockReport');
Route::get('/admin/branches', 'AdminController@branches');
Route::get('/admin/suppliers', 'AdminController@suppliers');
Route::get('/admin/branch-sale', 'AdminController@branchSale');
Route::get('/admin/branch-sale/{branch_id}', 'AdminController@salePerBranch');
Route::post('/admin/branch-sale/ajax', 'AdminController@ajaxMonth');
Route::get('/admin/branch-sale/{branch_id}/perMonth', 'AdminController@perMonth');
Route::get('/admin/reset', 'AdminController@reset');
Route::get('/admin/users', 'AdminController@users');

Route::post('/admin/reset-products', 'ProductController@resetProduct');
Route::get('/admin/get-reset', 'ProductController@getReset');
Route::post('/admin/undo-reset', 'ProductController@undoReset');
Route::get('/edit-receipt', 'AdminController@editReceipt');

//getuser
Route::get('/admin/get-users', 'UserController@getUsers');

//products
Route::get('/getproducts', 'ProductController@getProducts');
Route::post('/add-product', 'ProductController@addProduct');
Route::post('/update-product', 'ProductController@updateProduct');
Route::post('/delete-product', 'ProductController@deleteProduct');
Route::get('/product-cart', 'ProductController@productCart');
Route::post('/add-cart', 'ProductController@addToCart');
Route::post('/remove-cart', 'ProductController@removeToCart');
Route::post('/print-cart', 'ProductController@printCart');
Route::post('/get-category', 'ProductController@getCategory');


Route::post('/save-products', 'ProductController@saveProducts');



//sales report
Route::get('/sales-report', 'ReportController@getSalesReport');
Route::post('/edit-daily', 'ReportController@editDailySale');


//receipts
Route::get('/invoice', 'ReceiptController@invoice');
Route::post('/get-receipts', 'ReceiptController@getReciepts');
Route::post('/get-receipts-in', 'ReceiptController@getRecieptsIn');
//edit receipts
Route::post('/delete-temp', 'ReceiptController@deleteTemoEditCart');
//
Route::post('/delete-receipt', 'ReceiptController@deleteReceipt');

Route::get('/receipt-in', 'ReceiptController@receiptInInovice');




//pricelist
Route::get('/pricelist', 'ReportController@priceList');
Route::get('/stocklist', 'ReportController@stockList');

//branch
Route::get('/get-branch', 'BranchController@getBranch');
Route::post('/add-branch', 'BranchController@addBranch');
Route::post('/update-branch', 'BranchController@updateBranch');
Route::post('/delete-branch', 'BranchController@deleteBranch');
//supplier
//branch
Route::get('/get-supplier', 'SupplierController@getSupplier');
Route::post('/add-supplier', 'SupplierController@addSupplier');
Route::post('/update-supplier', 'SupplierController@updateSupplier');
Route::post('/delete-supplier', 'SupplierController@deleteSupplier');



//users
Route::get('/user/dashboard', 'AdminController@dashboard');

