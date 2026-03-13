<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\VehicleOptionController;
use App\Http\Controllers\Api\UserAdController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\Admin\AdminVehicleController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\PartnerController as ApiPartnerController;

/* |-------------------------------------------------------------------------- | API Routes |-------------------------------------------------------------------------- | | Here is where you can register API routes for your application. These | routes are loaded by the RouteServiceProvider and all of them will | be assigned to the "api" middleware group. Make something great! | */

// Public API route for registration
Route::post('/register', [RegisterController::class , 'register']);
Route::post('/login', [RegisterController::class , 'login']);

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

Route::post('/logout', [RegisterController::class , 'logout'])->middleware('auth:sanctum');
Route::post('/profile/update', [RegisterController::class , 'updateProfile'])->middleware('auth:sanctum');

// Forgot Password Routes
Route::post('/forgot-password', [ForgotPasswordController::class , 'sendResetEmail']);
Route::post('/verify-reset-token', [ForgotPasswordController::class , 'verifyToken']);
Route::post('/reset-password', [ForgotPasswordController::class , 'resetPassword']);

// Public Vehicle Options API
Route::get('/vehicle-options', [VehicleOptionController::class , 'getOptions']);
Route::get('/brands', [VehicleOptionController::class , 'getBrands']);
Route::get('/brands/{brandId}/models', [VehicleOptionController::class , 'getModels']);

// Public Ads API Routes
// These routes use UserAdController and are open to all visitors.
Route::get('/ads', [UserAdController::class , 'index']);
Route::get('/ads/{id}', [UserAdController::class , 'show']);

// Public Partner API Routes
Route::get('/partners', [ApiPartnerController::class, 'index']);
Route::get('/partners/{idOrSlug}', [ApiPartnerController::class, 'show']);
Route::post('/partners/{partnerId}/reviews', [ApiPartnerController::class, 'storeReview']);

// Legacy vehicle routes (kept for backwards compatibility)
Route::get('/vehicles', [VehicleController::class , 'index']);
Route::get('/vehicles/{id}', [VehicleController::class , 'show']);

// Authenticated Ad Routes (user must be logged in)
// IMPORTANT: /ads/my MUST be declared before /ads/{id} to prevent Laravel
// from matching the literal string 'my' as an {id} parameter.
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/ads/my', [UserAdController::class , 'myAds']);
    Route::post('/ads', [UserAdController::class , 'store']);
    Route::put('/ads/{id}', [UserAdController::class , 'update']);
    Route::delete('/ads/{id}', [UserAdController::class , 'destroy']);
    Route::patch('/ads/{id}/status', [UserAdController::class , 'changeStatus']);

    // Favorite Routes
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites/{vehicleId}/toggle', [FavoriteController::class, 'toggle']);
});


// Admin Routes
// Admin Routes
Route::group(['prefix' => 'admin', 'middleware' => 'auth:sanctum'], function () {
    // Vehicle / Ad Management
    Route::get('/brands/{brandId}/models', [AdminVehicleController::class , 'getModelsByBrand']);
    Route::get('/vehicles', [AdminVehicleController::class , 'index']);
    Route::get('/vehicles/{id}', [AdminVehicleController::class , 'show']);
    Route::post('/vehicles', [AdminVehicleController::class , 'store']);
    Route::put('/vehicles/{id}', [AdminVehicleController::class , 'update']);
    Route::delete('/vehicles/{id}', [AdminVehicleController::class , 'destroy']);
    Route::patch('/vehicles/{id}/status', [AdminVehicleController::class , 'changeStatus']);
    Route::patch('/vehicles/{id}/featured', [AdminVehicleController::class , 'toggleFeatured']);

    // Role Management
    Route::middleware('role:admin')->group(function () {
            Route::get('/roles', [RoleController::class , 'index']);
            Route::post('/roles', [RoleController::class , 'store']);
            Route::get('/roles/{id}', [RoleController::class , 'show']);
            Route::put('/roles/{id}', [RoleController::class , 'update']);
            Route::delete('/roles/{id}', [RoleController::class , 'destroy']);
            Route::get('/permissions', [RoleController::class , 'permissions']);

            // User Management
            Route::get('/users', [UserController::class , 'index']);
            Route::post('/users', [UserController::class , 'store']);
            Route::get('/users/{id}', [UserController::class , 'show']);
            Route::put('/users/{id}', [UserController::class , 'update']);
            Route::delete('/users/{id}', [UserController::class , 'destroy']);
        }
        );    });