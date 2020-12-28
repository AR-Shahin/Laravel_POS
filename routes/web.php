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



