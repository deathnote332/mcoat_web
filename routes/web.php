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

Route::get('/', 'HomeController@index');





//Admin
Route::get('/admin/dashboard', 'AdminController@dashboard');
Route::get('/admin/products', 'AdminController@products');
Route::get('/admin/manage-products', 'AdminController@manageProducts');
Route::get('/admin/product-out', 'AdminController@productOut');
Route::get('/admin/product-in', 'AdminController@productIn');
Route::get('/admin/receipts', 'AdminController@receipts');
Route::get('/admin/receipts-in', 'AdminController@receiptsIn');
Route::get('/admin/receipts-exchange', 'AdminController@receiptsExchange');
Route::get('/admin/receipts-purchase', 'AdminController@receiptsPurchase');
Route::get('/admin/stock-report', 'AdminController@stockReport');
Route::get('/admin/branches', 'AdminController@branches');
Route::get('/admin/suppliers', 'AdminController@suppliers');
Route::get('/admin/product-tracking', 'AdminController@productTracking');
Route::get('/admin/product-tracking-in', 'AdminController@productTrackingIn');
Route::get('/admin/product-tracking-delivery', 'AdminController@productTrackingDelivery');
Route::get('/admin/stock-exchange', 'AdminController@stockExchange');
Route::get('/admin/activity-logs', 'AdminController@activityLogs');
Route::get('/admin/purchase-order', 'AdminController@purchaseOrder');

//report
Route::get('/print-report', 'AdminController@printBranchReport');
Route::get('/generate/{type}/{branch}/{date}', 'ReceiptController@generate_report');

Route::get('/branch-sale', 'SaleController@branchSale');
Route::get('/branch-sale/{branch_id}', 'SaleController@salePerBranch');
Route::post('/branch-sale/ajax', 'SaleController@ajaxMonth');
Route::get('/branch-sale/{branch_id}/perMonth', 'SaleController@perMonth');

Route::get('/admin/reset', 'AdminController@reset');
Route::get('/admin/users', 'AdminController@users');


Route::post('/admin/reset-products', 'ProductController@resetProduct');
Route::get('/admin/get-reset', 'ProductController@getReset');
Route::post('/admin/undo-reset', 'ProductController@undoReset');

Route::get('/edit-receipt', 'ReceiptController@editReceipt');

//product-tracking
Route::get('/get-product-tracking/{warehouse_id}', 'ReportController@getProductTracking')->name('product-tracking');
Route::get('/get-product-tracking-in/{warehouse_id}', 'ReportController@getProductTrackingIn')->name('product-tracking-in');
Route::get('/get-product-tracking-delivery/{warehouse_id}', 'ReportController@getProductDelivery')->name('product-tracking-delivery');
Route::get('/user/product-tracking', 'SecretaryController@productTracking');
Route::get('/user/product-tracking-in', 'AdminController@productTrackingIn');
Route::get('/user/product-tracking-delivery', 'AdminController@productTrackingDelivery');
//inventory
Route::get('/branch-total-inventory', 'AdminController@branchTotalInventory');
Route::post('/save-inventory', 'ProductController@saveInventory');
Route::get('/inventory-list', 'AdminController@inventoryList');
Route::post('/get-inventory', 'ReportController@getInventory');
Route::get('/manage-inventory', 'AdminController@inventoryList');
Route::post('/delete-inventory', 'ProductController@deleteInventory');
Route::get('/print-inventory', 'ReportController@printInventory');
//getuser 
Route::get('/admin/get-users', 'UserController@getUsers');
Route::post('/admin/update-user', 'UserController@updateUser');

//products
Route::get('/getproducts', 'ProductController@getProducts')->name('getproducts');
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
Route::post('/get-stock-exchange', 'ReceiptController@getStockExchange');
Route::post('/get-purchase', 'ReceiptController@getPurchaseOrder');
//edit receipts
Route::post('/delete-temp', 'ProductController@deleteTempEditCart');
//
Route::post('/delete-receipt', 'ReceiptController@deleteReceipt');

Route::get('/receipt-in', 'ReceiptController@receiptInInovice');

Route::get('/monthly-sales', 'ReceiptController@monthlySales');




//pricelist
Route::get('/pricelist', 'ReportController@priceList');
Route::get('/stocklist', 'ReportController@stockList');

//branch
Route::get('/get-branch', 'BranchController@getBranch');
Route::post('/add-branch', 'BranchController@addBranch');
Route::post('/update-branch', 'BranchController@updateBranch');
Route::post('/delete-branch', 'BranchController@deleteBranch');
//supplier

Route::get('/get-supplier', 'SupplierController@getSupplier');
Route::post('/add-supplier', 'SupplierController@addSupplier');
Route::post('/update-supplier', 'SupplierController@updateSupplier');
Route::post('/delete-supplier', 'SupplierController@deleteSupplier');
Route::post('/get-supplier-products', 'SupplierController@getSupplierProducts');
Route::post('/add-supplier-products', 'SupplierController@addSupplierProducts');
Route::post('/remove-supplier-brand', 'SupplierController@removeSupplierBrand');

//secretary
Route::get('/user/dashboard', 'SecretaryController@dashboard');
Route::get('/user/products', 'SecretaryController@products');
Route::get('/user/manage-products', 'SecretaryController@manageProducts');
Route::get('/user/product-out', 'SecretaryController@productOut');
Route::get('/user/product-in', 'SecretaryController@productIn');
Route::get('/user/receipts', 'SecretaryController@receipts');
Route::get('/user/receipts-exchange', 'SecretaryController@receiptsExchange');
Route::get('/user/receipts-in', 'SecretaryController@receiptsIn');
Route::get('/user/stock-report', 'SecretaryController@stockReport');
Route::get('/user/suppliers', 'SecretaryController@suppliers');
Route::get('/user/branches', 'SecretaryController@branches');
Route::get('/user/stock-exchange', 'SecretaryController@stockExchange');

//print stock exhange
Route::get('/ajax-exchange', 'ProductController@ajaxExchange');
Route::post('/print-stock-exchange', 'ProductController@printStockExhange');
Route::get('/stock-invoice', 'ReceiptController@invoiceStockExchange');
Route::get('/edit-stock-receipt', 'ReceiptController@editStockReceipt');
Route::post('/update-stock-order', 'ReceiptController@saveEditStockReceipt');
//logs

Route::get('/get-logs', 'ReportController@getUserLogs')->name('get-logs');

//
Route::get('/purchase-order', 'ReceiptController@invoicePurchaseOrder');
Route::post('/save-purchase-order', 'ReceiptController@savePurchaseOrder');
Route::get('/edit-purchase-receipt', 'ReceiptController@editPurchaseReceipt');
Route::post('/update-purchase-order', 'ReceiptController@saveEditPurchaseReceipt');
Route::post('/delete-purchase-order', 'ReceiptController@deletePurchaseReceipt');