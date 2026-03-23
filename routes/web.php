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
use App\Http\Controllers\backend\admin_loginController;
use App\Http\Controllers\backend\admin_registerController;
use App\Http\Controllers\backend\supplier_dashboardController;
use App\Http\Controllers\backend\StockController;


use App\Http\Controllers\backend\SettingController;


use App\Http\Controllers\backend\AuditLogExportController;

// Auth Routes (Public)
Route::controller(admin_loginController::class)->group(function () {
    Route::get('/admin_login', 'index')->name('admin_login.index');
    Route::get('/login', 'index')->name('login'); // Standard Laravel login name
    Route::post('/admin_login', 'login')->name('admin_login.submit');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(admin_registerController::class)->group(function () {
    Route::get('/admin_register', 'index')->name('admin_register.index');
    Route::post('/admin_register', 'store')->name('admin_register.store');
});

// Protected Backend Routes
Route::middleware(['auth'])->group(function () {
    
    // Shared Routes (All authenticated users)
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard.index');
    });

    Route::controller(SettingController::class)->group(function () {
        Route::get('/setting', 'index')->name('setting.index');
        Route::post('/setting/update', 'update')->name('setting.update');
    });

    Route::get('/export-activity', [AuditLogExportController::class, 'exportCsv'])->name('export.activity');

    // Admin & Staff Only
    Route::middleware(['role:admin,staff'])->group(function () {
        // suppliers category
        Route::controller(SupplierController::class)->group(function () {
            Route::get('/suppliers', 'index')->name('suppliers.index');
            Route::post('/suppliers/brand/store', 'storeBrand')->name('suppliers.brand.store');
            Route::put('/suppliers/brand/{brand}/update', 'updateBrand')->name('suppliers.brand.update');
            Route::post('/suppliers/category/store', 'storeCategory')->name('suppliers.category.store');
            Route::delete('/suppliers/brand/{brand}', 'deleteBrand')->name('suppliers.brand.delete');
            Route::delete('/suppliers/category/{category}', 'deleteCategory')->name('suppliers.category.delete');
            Route::post('/suppliers/supplier/store', 'storeSupplier')->name('suppliers.supplier.store');
            Route::put('/suppliers/supplier/{supplier}/update', 'updateSupplier')->name('suppliers.supplier.update');
            Route::delete('/suppliers/supplier/{supplier}', 'deleteSupplier')->name('suppliers.supplier.delete');
            
            // New Approval Routes
            Route::get('/suppliers/approvals', 'approvals')->name('suppliers.approvals');
            Route::post('/suppliers/approve/{id}', 'approve')->name('suppliers.approve');
            Route::post('/suppliers/deny/{id}', 'deny')->name('suppliers.deny');
        });

        Route::controller(product_managementController::class)->group(function () {
            Route::get('/product_management', 'index')->name('product_management.index');
            Route::get('/cart', 'getCart')->name('cart.get');
            Route::post('/cart/add', 'addToCart')->name('cart.add');
            Route::post('/cart/update', 'updateCartQty')->name('cart.update');
            Route::delete('/cart/remove/{key}', 'removeFromCart')->name('cart.remove');
        });

        Route::controller(purchase_ordersController::class)->group(function () {
            Route::get('/purchase_orders', 'index')->name('purchase_orders.index');
            Route::post('/purchase_orders/store', 'store')->name('purchase_orders.store');
            Route::get('/purchase_orders/confirm_payment', 'confirmPayment')->name('purchase_orders.confirm_payment');
        });

        Route::controller(supplier_ordersController::class)->group(function () {
            Route::get('/supplier_orders', 'index')->name('supplier_orders.index');
        });

        Route::controller(goods_receivingController::class)->group(function () {
            Route::get('/goods_receiving', 'index')->name('goods_receiving.index');
            Route::post('/goods_receiving/{id}/approve', 'approve')->name('goods_receiving.approve');
            Route::post('/goods_receiving/{id}/reject', 'reject')->name('goods_receiving.reject');
            Route::post('/goods_receiving/store-location', 'storeLocation')->name('goods_receiving.store_location');
        });

        Route::controller(supplier_returnsController::class)->group(function () {
            Route::get('/supplier_returns', 'index')->name('supplier_returns.index');
        });

        Route::controller(approved_good_stockController::class)->group(function () {
            Route::get('/approved_good_stock', 'index')->name('approved_good_stock.index');
            Route::post('/approved_good_stock/add-to-stock', 'addToStock')->name('approved_good_stock.add_to_stock');
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

        Route::controller(customersController::class)->group(function () {
            Route::get('/customers', 'index')->name('customers.index');
            Route::post('/customers/store', 'store')->name('customers.store');
            Route::put('/customers/{id}', 'update')->name('customers.update');
            Route::delete('/customers/{id}', 'destroy')->name('customers.destroy');
        });

        Route::controller(sales_orderController::class)->group(function () {
            Route::get('/sales_order', 'index')->name('sales_order.index');
            Route::post('/sales_order/store', 'store')->name('sales_order.store');
            Route::get('/sales_order/confirm/{id}', 'confirm')->name('sales_order.confirm_sale');
        });

        Route::controller(sales_order_historyController::class)->group(function () {
            Route::get('/sales_order_history', 'index')->name('sales_order_history.index');
        });

        Route::controller(customer_paymentsController::class)->group(function () {
            Route::get('/customer_payments', 'index')->name('customer_payments.index');
            Route::post('/customer_payments/{id}/receive', 'processPayment')->name('customer_payments.receive');
        });

        Route::controller(sale_reportController::class)->group(function () {
            Route::get('/sale_report', 'index')->name('sale_report.index');
        });

        Route::controller(profit_reportController::class)->group(function () {
            Route::get('/profit_report', 'index')->name('profit_report.index');
        });

        Route::post('/stock/add-approved', [StockController::class, 'addApproved'])->name('stock.addApproved');
    });

    // Product details (AJAX) - allowed for admin, staff, and potentially suppliers if they need to see what they are offering
    Route::get('/product_management/{id}/details', [product_managementController::class, 'details'])->name('product_management.details');

    // Supplier Only
    Route::middleware(['role:supplier'])->group(function () {
        Route::controller(supplier_dashboardController::class)->group(function () {
            Route::get('/Supplier_Dashboard', 'index')->name('Supplier_Dashboard.index');
            Route::get('/Supplier_Dashboard/create', 'create')->name('Supplier_Dashboard.create');
            Route::get('/Supplier_Dashboard/{id}/edit', 'edit')->name('Supplier_Dashboard.edit');
            Route::post('/Supplier_Dashboard/offer', 'storeOffer')->name('Supplier_Dashboard.offer.store');
            Route::put('/Supplier_Dashboard/offer/{id}', 'updateOffer')->name('Supplier_Dashboard.offer.update');
            Route::delete('/Supplier_Dashboard/offer/{id}', 'deleteOffer')->name('Supplier_Dashboard.offer.delete');
            Route::get('/Supplier_Dashboard/disputes', 'disputes')->name('Supplier_Dashboard.disputes');
            Route::post('/Supplier_Dashboard/dispute/{id}/resolve', 'resolveDispute')->name('Supplier_Dashboard.dispute.resolve');
        });

        Route::controller(supplier_ordersController::class)->group(function () {
            Route::get('/supplier/orders', 'manage')->name('supplier.orders.manage');
            Route::post('/supplier/orders/{id}/status', 'updateStatus')->name('supplier.orders.update_status');
        });
    });
});


