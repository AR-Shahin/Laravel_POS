<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login','backend\LoginController@showLoginForm')->name('admin.login');
Route::post('login','backend\LoginController@login')->name('admin.login');

Route::middleware(['auth'])->group(function () {
    #Auth Routes
    Route::get('logout','backend\LoginController@logout')->name('logout');
    #Dashboard
    Route::get('dashboard', 'backend\DashboardController@dashboard')->name('dashboard');

#Unit Routes
    Route::get('unit/index', 'backend\UnitController@index')->name('unit.index');
    Route::get('unit/get', 'backend\UnitController@getAllUnits')->name('get.unit');
    Route::delete('unit/delete', 'backend\UnitController@destroy')->name('unit.destroy');
    Route::post('/unit/store', 'backend\UnitController@store')->name('unit.store');
    Route::get('/unit/status-active', 'backend\UnitController@makeActive')->name('unit.status.active');
    Route::get('/unit/status-inactive', 'backend\UnitController@makeInactive')->name('unit.status.inactive');
    Route::get('/unit/edit', 'backend\UnitController@edit')->name('unit.edit');
    Route::post('/unit/update', 'backend\UnitController@update')->name('unit.update');

#Category Routes
    Route::get('category/index', 'backend\CategoryController@index')->name('category.index');
    Route::get('category/get', 'backend\CategoryController@getAllCategory')->name('get.category');
    Route::delete('category/delete', 'backend\CategoryController@destroy')->name('category.destroy');
    Route::post('/category/store', 'backend\CategoryController@store')->name('category.store');
    Route::get('/category/status-active', 'backend\CategoryController@makeActive')->name('category.status.active');
    Route::get('/category/status-inactive', 'backend\CategoryController@makeInactive')->name('category.status.inactive');
    Route::get('/category/edit', 'backend\CategoryController@edit')->name('category.edit');
    Route::post('/category/update', 'backend\CategoryController@update')->name('category.update');

#Suppliers Routes
    Route::prefix('supplier')->group(function () {
        Route::get('index', 'backend\SupplierController@index')->name('supplier.index');
        Route::get('fetch', 'backend\SupplierController@getAllSupplier')->name('supplier.fetch');
        Route::post('store', 'backend\SupplierController@store')->name('supplier.store');
        Route::get('edit', 'backend\SupplierController@edit')->name('supplier.edit');
        Route::put('update', 'backend\SupplierController@update')->name('supplier.update');
        Route::delete('delete', 'backend\SupplierController@destroy')->name('supplier.delete');
    });

#Customer Routes
    Route::prefix('customer')->group(function () {
        Route::get('index', 'backend\CustomerController@index')->name('customer.index');
        Route::get('fetch', 'backend\CustomerController@getAllCustomer')->name('customer.fetch');
        Route::post('store', 'backend\CustomerController@store')->name('customer.store');
        Route::get('edit', 'backend\CustomerController@edit')->name('customer.edit');
        Route::put('update', 'backend\CustomerController@update')->name('customer.update');
        Route::delete('delete', 'backend\CustomerController@destroy')->name('customer.delete');
    });

#Products Routes
    Route::prefix('product')->group(function () {
        Route::get('index', 'backend\ProductController@index')->name('product.index');
        Route::get('fetch', 'backend\ProductController@getAllProduct')->name('product.fetch');
        Route::post('store', 'backend\ProductController@store')->name('product.store');
        Route::get('edit', 'backend\ProductController@edit')->name('product.edit');
        Route::put('update', 'backend\ProductController@update')->name('product.update');
        Route::delete('delete', 'backend\ProductController@destroy')->name('product.delete');
        Route::put('status-active', 'backend\ProductController@makeActive')->name('product.status.active');
        Route::put('status-inactive', 'backend\ProductController@makeInactive')->name('product.status.inactive');
    });

    #Admin Routes
    Route::prefix('admin')->group(function () {
        Route::get('index', 'backend\AdminController@index')->name('admin.index');
        Route::get('fetch', 'backend\AdminController@getAllAdmin')->name('admin.fetch');
        Route::post('store', 'backend\AdminController@store')->name('admin.store');
        Route::get('edit', 'backend\AdminController@edit')->name('admin.edit');
        Route::put('update', 'backend\AdminController@update')->name('admin.update');
        Route::delete('delete', 'backend\AdminController@destroy')->name('admin.delete');
        Route::put('status-active', 'backend\AdminController@makeActive')->name('admin.status.active');
        Route::put('status-inactive', 'backend\AdminController@makeInactive')->name('admin.status.inactive');
    });

    #Purchase Routes
    Route::prefix('purchase')->group(function () {
        Route::get('index', 'backend\PurchaseController@index')->name('purchase.index');
        Route::get('fetch', 'backend\PurchaseController@getAllPurchase')->name('purchase.fetch');
        Route::post('store', 'backend\PurchaseController@store')->name('purchase.store');
        Route::delete('delete', 'backend\PurchaseController@destroy')->name('purchase.destroy');
        Route::put('approve-purchase', 'backend\PurchaseController@approvePurchaseItem')->name('approve-purchase');
    });

    #Invoice Routes
    Route::prefix('invoice')->name('invoice.')->namespace('backend')->group(function () {
        Route::get('index', 'InvoiceController@index')->name('index');
        Route::get('fetch', 'InvoiceController@getAllInvoice')->name('fetch');
        Route::post('store', 'InvoiceController@store')->name('store');
        Route::post('approve','InvoiceController@approve')->name('approve');
        Route::get('view','InvoiceController@view')->name('view');
        Route::delete('delete', 'InvoiceController@destroy')->name('destroy');
        Route::get('print/{id?}', 'InvoiceController@printInvoice')->name('print');

    });

#Report Controller
    Route::prefix('report')->name('report.')->namespace('backend')->group(function () {
        #credit
    Route::namespace('Report')->group(function (){
        Route::get('credit/customer','CreditCustomerController@index')->name('credit.customer');
        Route::get('get-credit/customer','CreditCustomerController@getAllCreditCustomers')->name('get-credit.customer');
        Route::post('update/invoice','CreditCustomerController@updateInvoiceAmount')->name('update.invoice');

        #purchase
        Route::get('purchase','PurchaseController@index')->name('purchase');
        Route::get('purchase/date','PurchaseController@dateWisePurchaseReport')->name('purchase.date');
    });
    });






    #Default Routes
    Route::get('get-all-suppliers','backend\DefaultController@getAllSuppliers')->name('get.suppliers');
    Route::get('get-all-category','backend\DefaultController@getAllCategory')->name('get.categories');
    Route::get('get-all-products','backend\DefaultController@getAllProduct')->name('get.purchase.products');
    Route::get('get-product-quantity','backend\DefaultController@getProductQuantity')->name('product.quantity');

    #invoice
    Route::get('get-all-category-invoice','backend\DefaultController@getAllCategoryForInvoiceModal')->name('get.categories.invoice');
    Route::get('get-all-customers-invoice','backend\DefaultController@getAllCustomerForInvoiceModal')->name('get.customers.invoice');

});

#password
//$2y$10$HhVnGWE/wVjrLsD2fxXHKuQ/5EH9vR8fa2Exu/p2FCheEyQ52jPwW




