<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\VehicleOptionController;
use App\Http\Controllers\Api\Admin\AdminVehicleController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API route for registration
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [RegisterController::class, 'login']);

Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    $user = $request->user();
    // Load roles and permissions for frontend authorization
    $user->load('roles.permissions');
    
    $userData = $user->toArray();
    
    // Add roles and permissions as simple arrays for easier use in Next.js
    $userData['roles'] = $user->getRoleNames();
    $userData['permissions'] = $user->getAllPermissions()->pluck('name');
    
    if ($user->profile_picture) {
        $userData['profile_picture_url'] = asset($user->profile_picture);
    }
    
    return response()->json([
        'success' => true,
        'data' => $userData
    ]);
});

Route::post('/logout', [RegisterController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/profile/update', [RegisterController::class, 'updateProfile'])->middleware('auth:sanctum');

// Forgot Password Routes
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetEmail']);
Route::post('/verify-reset-token', [ForgotPasswordController::class, 'verifyToken']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

// Public Vehicle Options API
Route::get('/vehicle-options', [VehicleOptionController::class, 'getOptions']);
Route::get('/brands', [VehicleOptionController::class, 'getBrands']);
Route::get('/brands/{brandId}/models', [VehicleOptionController::class, 'getModels']);

// Vehicle API Routes
Route::get('/vehicles', [VehicleController::class, 'index']);
Route::get('/vehicles/{id}', [VehicleController::class, 'show']);


// Admin Routes
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    // Existing Vehicle Routes
    Route::get('/brands/{brandId}/models', [AdminVehicleController::class, 'getModelsByBrand']);
    Route::get('/vehicles', [AdminVehicleController::class, 'index']);
    Route::get('/vehicles/{id}', [AdminVehicleController::class, 'show']);
    Route::post('/vehicles', [AdminVehicleController::class, 'store']);
    Route::put('/vehicles/{id}', [AdminVehicleController::class, 'update']);
    Route::delete('/vehicles/{id}', [AdminVehicleController::class, 'destroy']);

    // Role Management
    Route::middleware('role:admin')->group(function () {
        Route::get('/roles', [RoleController::class, 'index']);
        Route::post('/roles', [RoleController::class, 'store']);
        Route::get('/roles/{id}', [RoleController::class, 'show']);
        Route::put('/roles/{id}', [RoleController::class, 'update']);
        Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
        Route::get('/permissions', [RoleController::class, 'permissions']);

        // User Management
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });
});