<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\DashboardController;   // ← YOU MISSED THIS LINE
use App\Http\Controllers\backend\product_managementController;
use App\Http\Controllers\backend\SupplierController;


Route::controller(DashboardController::class)->group(function () {
    Route::get('/', 'index')->name('dashboard.index');
});


Route::controller(SupplierController::class)->group(function () {
    Route::get('/suppliers', 'index')->name('suppliers.index');
});

Route::controller(product_managementController::class)->group(function () {
    Route::get('/product_management', 'index')->name('product_management.index');
});