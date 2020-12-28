<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard','backend\DashboardController@dashboard')->name('dashboard');


#Unit Routes
Route::get('unit/index','backend\UnitController@index')->name('unit.index');
Route::get('unit/get','backend\UnitController@getAllUnits')->name('get.unit');
Route::delete('unit/delete', 'backend\UnitController@destroy')->name('unit.destroy');
Route::post('/unit/store', 'backend\UnitController@store')->name('unit.store');
Route::get('/unit/status-active', 'backend\UnitController@makeActive')->name('unit.status.active');
Route::get('/unit/status-inactive', 'backend\UnitController@makeInactive')->name('unit.status.inactive');
Route::get('/unit/edit', 'backend\UnitController@edit')->name('unit.edit');
Route::post('/unit/update', 'backend\UnitController@update')->name('unit.update');

#Category Routes
Route::get('category/index','backend\CategoryController@index')->name('category.index');
Route::get('category/get','backend\CategoryController@getAllCategory')->name('get.category');
Route::delete('category/delete', 'backend\CategoryController@destroy')->name('category.destroy');
Route::post('/category/store', 'backend\CategoryController@store')->name('category.store');
Route::get('/category/status-active', 'backend\CategoryController@makeActive')->name('category.status.active');
Route::get('/category/status-inactive', 'backend\CategoryController@makeInactive')->name('category.status.inactive');
Route::get('/category/edit', 'backend\CategoryController@edit')->name('category.edit');
Route::post('/category/update', 'backend\CategoryController@update')->name('category.update');




