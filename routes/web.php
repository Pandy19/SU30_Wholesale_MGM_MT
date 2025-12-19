<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\DashboardController;   // ← YOU MISSED THIS LINE
use App\Http\Controllers\backend\product_managementController;
use App\Http\Controllers\backend\SupplierController;
use App\Http\Controllers\backend\purchase_ordersController;
use App\Http\Controllers\backend\supplier_ordersController;

Route::controller(DashboardController::class)->group(function () {
    Route::get('/', 'index')->name('dashboard.index');
});


Route::controller(SupplierController::class)->group(function () {
    Route::get('/suppliers', 'index')->name('suppliers.index');
});

Route::controller(product_managementController::class)->group(function () {
    Route::get('/product_management', 'index')->name('product_management.index');
});

Route::controller(purchase_ordersController::class)->group(function () {
    Route::get('/purchase_orders', 'index')->name('purchase_orders.index');
});

Route::get('/purchase_orders/confirm_payment', function () {
    return view('backend.purchase_orders.confirm_payment');
})->name('purchase_orders.confirm_payment');

Route::controller(supplier_ordersController::class)->group(function () {
    Route::get('/supplier_orders', 'index')->name('supplier_orders.index');
});