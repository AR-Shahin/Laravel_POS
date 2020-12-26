<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard','backend\DashboardController@dashboard')->name('dashboard');



