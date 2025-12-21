<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\product_managementController;
use App\Http\Controllers\backend\SupplierController;
use App\Http\Controllers\backend\purchase_ordersController;
use App\Http\Controllers\backend\supplier_ordersController;
use App\Http\Controllers\backend\goods_receivingController;
use App\Http\Controllers\backend\supplier_returnsController;
use App\Http\Controllers\backend\approved_good_stockController;
use App\Http\Controllers\backend\stock_categorysController;
use App\Http\Controllers\backend\product_stock_listController;
use App\Http\Controllers\backend\stock_ledgerController;
use App\Http\Controllers\backend\customersController;
use App\Http\Controllers\backend\sales_orderController;
use App\Http\Controllers\backend\sales_invoiceController;
use App\Http\Controllers\backend\sales_order_historyController;
use App\Http\Controllers\backend\customer_paymentsController;
use App\Http\Controllers\backend\sale_reportController;
use App\Http\Controllers\backend\profit_reportController;


//dashboard
Route::controller(DashboardController::class)->group(function () {
    Route::get('/', 'index')->name('dashboard.index');
});


//suppliers category
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

Route::controller(goods_receivingController::class)->group(function () {
    Route::get('/goods_receiving', 'index')->name('goods_receiving.index');
});

Route::controller(supplier_returnsController::class)->group(function () {
    Route::get('/supplier_returns', 'index')->name('supplier_returns.index');
});


//stocks category
Route::controller(approved_good_stockController::class)->group(function () {
    Route::get('/approved_good_stock', 'index')->name('approved_good_stock.index');
});

Route::controller(stock_categorysController::class)->group(function () {
    Route::get('/stock_categorys', 'index')->name('stock_categorys.index');
});

Route::controller(product_stock_listController::class)->group(function () {
    Route::get('/product_stock_list', 'index')->name('product_stock_list.index');
});

Route::controller(stock_ledgerController::class)->group(function () {
    Route::get('/stock_ledger', 'index')->name('stock_ledger.index');
});

//Customers category 
Route::controller(customersController::class)->group(function () {
    Route::get('/customers', 'index')->name('customers.index');
});

Route::controller(sales_orderController::class)->group(function () {
    Route::get('/sales_order', 'index')->name('sales_order.index');
});

Route::get('/sales_order/confirm', function () {
    return view('backend.sales_order.confirm_sale');
})->name('sales_order.confirm_sale');

Route::controller(sales_order_historyController::class)->group(function () {
    Route::get('/sales_order_history', 'index')->name('sales_order_history.index');
});

Route::controller(customer_paymentsController::class)->group(function () {
    Route::get('/customer_payments', 'index')->name('customer_payments.index');
});


//reports category
Route::controller(sale_reportController::class)->group(function () {
    Route::get('/sale_report', 'index')->name('sale_report.index');
});

Route::controller(profit_reportController::class)->group(function () {
    Route::get('/profit_report', 'index')->name('profit_report.index');
});