<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HealthCheckController;
use Illuminate\Support\Facades\Route;

// Health check routes (tidak perlu authentication)
Route::get('/health-check', [HealthCheckController::class, 'check']);
Route::get('/status', [HealthCheckController::class, 'status']);

// Rute default: langsung ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute setelah login
Route::middleware('auth')->group(function () {
    // Dashboard umum - redirect berdasarkan role
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role === 'po') {
            return redirect()->route('po.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    })->name('dashboard');

    // Rute untuk Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Material Quantity Summary for Admin
        Route::get('/material-quantity-detail', [\App\Http\Controllers\Admin\DashboardController::class, 'materialQuantityDetail'])->name('material-quantity-detail');

        // Analytics Dashboard Routes
        Route::get('/analytics', [\App\Http\Controllers\Admin\DashboardController::class, 'analyticsDashboard'])->name('analytics.dashboard');
        Route::get('/analytics-data', [\App\Http\Controllers\Admin\DashboardController::class, 'getAnalyticsDataJson'])->name('analytics.data');
        Route::post('/export-analytics', [\App\Http\Controllers\Admin\DashboardController::class, 'exportAnalytics'])->name('export.analytics');

        // Export Routes
        Route::get('/export/transactions', [\App\Http\Controllers\Admin\DashboardController::class, 'exportTransactions'])->name('export.transactions');
        Route::get('/export/monthly-reports', [\App\Http\Controllers\Admin\DashboardController::class, 'exportMonthlyReports'])->name('export.monthly-reports');
        Route::get('/export/summary', [\App\Http\Controllers\Admin\DashboardController::class, 'exportSummary'])->name('export.summary');

        // User Management Routes
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::patch('/users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::patch('/users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');

        // Tabel Data Transaksi
        Route::get('/transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [\App\Http\Controllers\Admin\TransactionController::class, 'show'])->name('transactions.show');

        // Manajemen Data Master - Unified Page
        Route::get('/master-data', [\App\Http\Controllers\Admin\MasterDataPageController::class, 'index'])->name('master-data.index');

        // Vendor CRUD routes
        Route::post('/master-data/vendor', [\App\Http\Controllers\Admin\MasterDataController::class, 'storeVendor'])->name('master-data.vendor.store');
        Route::put('/master-data/vendor/{vendor}', [\App\Http\Controllers\Admin\MasterDataController::class, 'updateVendor'])->name('master-data.vendor.update');
        Route::delete('/master-data/vendor/{vendor}', [\App\Http\Controllers\Admin\MasterDataController::class, 'deleteVendor'])->name('master-data.vendor.delete');

        // Project CRUD routes
        Route::post('/master-data/project', [\App\Http\Controllers\Admin\MasterDataController::class, 'storeProject'])->name('master-data.project.store');
        Route::put('/master-data/project/{project}', [\App\Http\Controllers\Admin\MasterDataController::class, 'updateProject'])->name('master-data.project.update');
        Route::delete('/master-data/project/{project}', [\App\Http\Controllers\Admin\MasterDataController::class, 'deleteProject'])->name('master-data.project.delete');

        // Category CRUD routes
        Route::post('/master-data/category', [\App\Http\Controllers\Admin\MasterDataController::class, 'storeCategory'])->name('master-data.category.store');
        Route::put('/master-data/category/{category}', [\App\Http\Controllers\Admin\MasterDataController::class, 'updateCategory'])->name('master-data.category.update');
        Route::delete('/master-data/category/{category}', [\App\Http\Controllers\Admin\MasterDataController::class, 'deleteCategory'])->name('master-data.category.delete');

        // Material CRUD routes
        Route::post('/master-data/material', [\App\Http\Controllers\Admin\MasterDataController::class, 'storeMaterial'])->name('master-data.material.store');
        Route::put('/master-data/material/{material}', [\App\Http\Controllers\Admin\MasterDataController::class, 'updateMaterial'])->name('master-data.material.update');
        Route::delete('/master-data/material/{material}', [\App\Http\Controllers\Admin\MasterDataController::class, 'deleteMaterial'])->name('master-data.material.delete');

        // Monthly Reports Management
        Route::resource('monthly-reports', \App\Http\Controllers\Admin\MonthlyReportController::class);
        Route::patch('/monthly-reports/{monthlyReport}/update-status', [\App\Http\Controllers\Admin\MonthlyReportController::class, 'updateStatus'])->name('monthly-reports.update-status');
        Route::get('/monthly-reports/{monthlyReport}/download', [\App\Http\Controllers\Admin\MonthlyReportController::class, 'download'])->name('monthly-reports.download');
        Route::delete('/monthly-reports/{monthlyReport}', [\App\Http\Controllers\Admin\MonthlyReportController::class, 'destroy'])->name('monthly-reports.destroy');

        // Loss Reports Management
        Route::get('/loss-reports', [\App\Http\Controllers\Admin\LossReportController::class, 'index'])->name('loss-reports.index');
        Route::get('/loss-reports/{lossReport}', [\App\Http\Controllers\Admin\LossReportController::class, 'show'])->name('loss-reports.show');
        Route::patch('/loss-reports/{lossReport}/update-status', [\App\Http\Controllers\Admin\LossReportController::class, 'updateStatus'])->name('loss-reports.update-status');
        Route::get('/loss-reports/{lossReport}/download', [\App\Http\Controllers\Admin\LossReportController::class, 'download'])->name('loss-reports.download');

        // MFO Requests Management
        Route::get('/mfo-requests', [\App\Http\Controllers\Admin\MfoRequestController::class, 'index'])->name('mfo-requests.index');
        Route::get('/mfo-requests/{mfoRequest}', [\App\Http\Controllers\Admin\MfoRequestController::class, 'show'])->name('mfo-requests.show');
        Route::patch('/mfo-requests/{mfoRequest}/update-status', [\App\Http\Controllers\Admin\MfoRequestController::class, 'updateStatus'])->name('mfo-requests.update-status');
        Route::get('/mfo-requests/{mfoRequest}/download', [\App\Http\Controllers\Admin\MfoRequestController::class, 'download'])->name('mfo-requests.download');

        // PO Materials Management
        Route::get('/po-materials', [\App\Http\Controllers\Admin\PoMaterialController::class, 'index'])->name('po-materials.index');
        Route::get('/po-materials/{poMaterial}', [\App\Http\Controllers\Admin\PoMaterialController::class, 'show'])->name('po-materials.show');
        Route::patch('/po-materials/{poMaterial}/update-status', [\App\Http\Controllers\Admin\PoMaterialController::class, 'updateStatus'])->name('po-materials.update-status');
        // Alternative route for troubleshooting
        Route::post('/po-materials/{poMaterial}/update-status', [\App\Http\Controllers\Admin\PoMaterialController::class, 'updateStatus'])->name('po-materials.update-status-post');

        // PO Transports Management
        Route::get('/po-transports', [\App\Http\Controllers\Admin\PoTransportController::class, 'index'])->name('po-transports.index');
        Route::get('/po-transports/{poTransport}', [\App\Http\Controllers\Admin\PoTransportController::class, 'show'])->name('po-transports.show');
        Route::patch('/po-transports/{poTransport}/update-status', [\App\Http\Controllers\Admin\PoTransportController::class, 'updateStatus'])->name('po-transports.update-status');
        Route::get('/po-transports/{poTransport}/download', [\App\Http\Controllers\Admin\PoTransportController::class, 'download'])->name('po-transports.download');

        // Document Management for Admin
        Route::resource('documents', \App\Http\Controllers\Admin\DocumentController::class);
        Route::get('/documents/{document}/download', [\App\Http\Controllers\Admin\DocumentController::class, 'download'])->name('documents.download');
        Route::patch('/documents/{document}/toggle-status', [\App\Http\Controllers\Admin\DocumentController::class, 'toggleStatus'])->name('documents.toggle-status');

        // Export Routes for Admin (duplikasi dihapus - sudah ada di atas)

        // Legacy individual resource routes (keep for backward compatibility if needed)
        Route::resource('vendors', \App\Http\Controllers\Admin\VendorController::class);
        Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class);
        Route::resource('materials', \App\Http\Controllers\Admin\MaterialController::class);
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    });

    // Rute untuk PO (Purchase Order)
    Route::middleware('role:po')->prefix('po')->name('po.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Po\DashboardController::class, 'index'])->name('dashboard');

        // PO Materials routes
        Route::resource('po-materials', \App\Http\Controllers\Po\PoMaterialController::class);
        Route::patch('po-materials/{poMaterial}/update-status', [\App\Http\Controllers\Po\PoMaterialController::class, 'updateStatus'])->name('po-materials.update-status');
        Route::get('ajax/sub-projects', [\App\Http\Controllers\Po\PoMaterialController::class, 'getSubProjects'])->name('ajax.sub-projects');

        // Material Tracking System
        Route::get('/material-dashboard', [\App\Http\Controllers\Po\MaterialDashboardController::class, 'index'])->name('material-dashboard.index');
        
        // Material Receipt (Penerimaan Material)
        Route::get('/material-receipt', [\App\Http\Controllers\Po\MaterialReceiptController::class, 'index'])->name('material-receipt.index');
        Route::get('/material-receipt/{poMaterial}/create', [\App\Http\Controllers\Po\MaterialReceiptController::class, 'create'])->name('material-receipt.create');
        Route::post('/material-receipt/{poMaterial}', [\App\Http\Controllers\Po\MaterialReceiptController::class, 'store'])->name('material-receipt.store');
        Route::get('/material-receipt/{poMaterial}/show', [\App\Http\Controllers\Po\MaterialReceiptController::class, 'show'])->name('material-receipt.show');

        // Material Usage (Penggunaan Material)
        Route::get('/material-usage', [\App\Http\Controllers\Po\MaterialUsageController::class, 'index'])->name('material-usage.index');
        Route::get('/material-usage/create', [\App\Http\Controllers\Po\MaterialUsageController::class, 'create'])->name('material-usage.create');
        Route::post('/material-usage', [\App\Http\Controllers\Po\MaterialUsageController::class, 'store'])->name('material-usage.store');
        Route::get('/material-usage/{transaction}', [\App\Http\Controllers\Po\MaterialUsageController::class, 'show'])->name('material-usage.show');

        // Material Stock (Stock Material)
        Route::get('/material-stock', [\App\Http\Controllers\Po\MaterialStockController::class, 'index'])->name('material-stock.index');
        Route::get('/material-stock/{materialStock}', [\App\Http\Controllers\Po\MaterialStockController::class, 'show'])->name('material-stock.show');
    });

    // Rute untuk User (Staf Lapangan)
    Route::middleware('role:user')->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');

        // Transaction routes
        Route::resource('transactions', \App\Http\Controllers\User\TransactionController::class);
        Route::get('projects/{project}/sub-projects', [\App\Http\Controllers\User\TransactionController::class, 'getSubProjects'])
            ->name('projects.sub-projects');
        Route::get('projects/{project}/sub-projects/{subProject}/materials', [\App\Http\Controllers\User\TransactionController::class, 'getMaterialsByProject'])
            ->name('projects.sub-projects.materials');

        // Monthly Reports for Users
        Route::resource('monthly-reports', \App\Http\Controllers\User\MonthlyReportController::class);
        Route::get('monthly-reports/{monthlyReport}/download', [\App\Http\Controllers\User\MonthlyReportController::class, 'download'])->name('monthly-reports.download');

        // Loss Reports for Users
        Route::resource('loss-reports', \App\Http\Controllers\User\LossReportController::class);
        Route::get('loss-reports/{lossReport}/download', [\App\Http\Controllers\User\LossReportController::class, 'download'])->name('loss-reports.download');

        // MFO Requests for Users
        Route::resource('mfo-requests', \App\Http\Controllers\User\MfoRequestController::class);
        Route::get('mfo-requests/{mfoRequest}/download', [\App\Http\Controllers\User\MfoRequestController::class, 'download'])->name('mfo-requests.download');
        Route::get('ajax/sub-projects', [\App\Http\Controllers\User\MfoRequestController::class, 'getSubProjects'])->name('user.ajax.sub-projects');

        // PO Transports for Users
        Route::resource('po-transports', \App\Http\Controllers\User\PoTransportController::class);
        Route::get('po-transports/{poTransport}/download', [\App\Http\Controllers\User\PoTransportController::class, 'download'])->name('po-transports.download');

        // Document Access for Users (Field Workers)
        Route::get('/documents', [\App\Http\Controllers\User\DocumentController::class, 'index'])->name('documents.index');
        Route::get('/documents/{document}/download', [\App\Http\Controllers\User\DocumentController::class, 'download'])->name('documents.download');
    });

    // Rute Profil (umum untuk semua role)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notification routes (untuk semua role)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [\App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::get('/recent', [\App\Http\Controllers\NotificationController::class, 'getRecent'])->name('recent');
        Route::patch('/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('read');
        Route::patch('/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/', [\App\Http\Controllers\NotificationController::class, 'clear'])->name('clear');
    });
});

//requiere dir aja ini mah ygy
require __DIR__.'/auth.php';
