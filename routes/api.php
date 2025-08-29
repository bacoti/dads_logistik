<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Project; // Pastikan Anda mengimpor modelnya
use App\Http\Controllers\Api\MasterDataController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Protected Master Data API Routes
Route::middleware(['web'])->group(function () {
    // Rute untuk mengambil sub-project dinamis (dipindahkan ke dalam middleware)
    Route::get('/sub-projects/{project}', function (Project $project) {
        return response()->json($project->subProjects);
    });
    
    // Master Data Management API Routes
    Route::prefix('master-data')->group(function () {
        Route::get('/init', [MasterDataController::class, 'init']);
        Route::get('/projects/{project}/sub-projects', [MasterDataController::class, 'getSubProjects']);
        Route::get('/materials', [MasterDataController::class, 'getMaterials']); // Get all materials
        Route::get('/sub-projects/{subProjectId}/categories', [MasterDataController::class, 'getCategories']); // Get categories by sub project
        Route::get('/sub-projects/{subProjectId}/materials', [MasterDataController::class, 'getSubProjectMaterials']); // Get materials by sub project
        
        // CRUD Routes under master-data prefix
        Route::post('/vendors', [MasterDataController::class, 'storeVendor']);
        Route::post('/projects', [MasterDataController::class, 'storeProject']);
        Route::post('/sub-projects', [MasterDataController::class, 'storeSubProject']);
        Route::post('/categories', [MasterDataController::class, 'storeCategory']);
        Route::post('/materials', [MasterDataController::class, 'storeMaterial']);

        Route::delete('/vendors/{id}', [MasterDataController::class, 'deleteVendor']);
        Route::delete('/projects/{id}', [MasterDataController::class, 'deleteProject']);
        Route::delete('/sub-projects/{id}', [MasterDataController::class, 'deleteSubProject']);
        Route::delete('/categories/{id}', [MasterDataController::class, 'deleteCategory']);
        Route::delete('/materials/{id}', [MasterDataController::class, 'deleteMaterial']);
    });
});
