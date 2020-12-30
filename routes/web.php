<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login','backend\LoginController@showLoginForm')->name('admin.login');
Route::post('login','backend\LoginController@login')->name('admin.login');

Route::middleware(['auth'])->group(function () {
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

});




