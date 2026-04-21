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
use App\Http\Controllers\Api\VehicleInquiryController;
use App\Http\Controllers\Api\CompareController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Api\ShareController;

/* |-------------------------------------------------------------------------- | API Routes |-------------------------------------------------------------------------- | | Here is where you can register API routes for your application. These | routes are loaded by the RouteServiceProvider and all of them will | be assigned to the "api" middleware group. Make something great! | */

// Public Social Sharing API
Route::get('/share', [ShareController::class, 'index']);

// Vehicle Viewing & Inquiry API
Route::post('/vehicles/{vehicleId}/inquiry', [\App\Http\Controllers\Api\VehicleViewingController::class, 'inquiry']);
Route::post('/vehicles/{vehicleId}/trade-offer', [\App\Http\Controllers\Api\TradeOfferController::class, 'store']);
Route::post('/vehicles/{vehicleId}/viewing-request', [\App\Http\Controllers\Api\VehicleViewingController::class, 'store']);

// Contact Us API
Route::post('/contact-us', [\App\Http\Controllers\Api\ContactController::class, 'store']);

// Newsletter
Route::post('/newsletter/subscribe', [\App\Http\Controllers\Api\NewsletterController::class, 'subscribe']);

// Public Subscription API
Route::get('/subscriptions/plans', [SubscriptionController::class, 'index']);

// Public Settings API (Header, Footer, Social links, etc.)
Route::get('/settings', [\App\Http\Controllers\Api\SettingController::class, 'index']);

// Public CMS API (Dynamic Sections)
Route::get('/cms/blog-posts/all', [\App\Http\Controllers\Api\CMSController::class, 'getBlogPosts']);
Route::get('/cms/items/{id}', [\App\Http\Controllers\Api\CMSController::class, 'showItem']);
Route::get('/cms/{slug}', [\App\Http\Controllers\Api\CMSController::class, 'show']);

// Public API route for registration
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/verify-email', [RegisterController::class, 'verifyEmail'])->name('api.verify-email');
Route::post('/login', [RegisterController::class, 'login']);

Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    $user = $request->user();
    // Load roles and permissions for frontend authorization
    $user->load('roles.permissions', 'activeSubscription.plan');

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
Route::get('/brands-body-types', [VehicleOptionController::class, 'getBrandsBodyTypes']);
Route::get('/body-types', [VehicleOptionController::class, 'getBodyTypes']);

// Public Ads API Routes
// These routes use UserAdController and are open to all visitors.
Route::get('/ad', [UserAdController::class, 'index']);
Route::get('/ads/{id}', [UserAdController::class, 'show']);
Route::get('/compare', [CompareController::class, 'compare']);

// Public Partner API Routes
Route::get('/partners', [ApiPartnerController::class, 'index']);
Route::get('/partners/filters', [ApiPartnerController::class, 'getFiltersData']);
Route::get('/partners/{idOrSlug}', [ApiPartnerController::class, 'show']);
Route::post('/partners/{partnerId}/reviews', [ApiPartnerController::class, 'storeReview']);

// Legacy vehicle routes (kept for backwards compatibility)
Route::get('/vehicles', [VehicleController::class, 'index']);
Route::get('/vehicles/{id}', [VehicleController::class, 'show']);
Route::get('/vehicles/{id}/related', [VehicleController::class, 'similarVehiclesByUser']);

// Authenticated Ad Routes (user must be logged in)
// IMPORTANT: /ads/my MUST be declared before /ads/{id} to prevent Laravel
// from matching the literal string 'my' as an {id} parameter.
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/garage', [UserAdController::class, 'garage']);
    Route::get('/ads/my', [UserAdController::class, 'myAds']);
    Route::post('/ads', [UserAdController::class, 'store']);
    Route::match(['get', 'post'], '/ads/{id}/edit', [UserAdController::class, 'edit']);
    Route::post('/ads/{id}', [UserAdController::class, 'update']); // Combined update method using POST for file support
    Route::delete('/ads/{id}', [UserAdController::class, 'destroy']);
    Route::patch('/ads/{id}/status', [UserAdController::class, 'changeStatus']);

    // Trade Offer from Garage
    Route::post('/vehicles/{vehicleId}/trade-offer/garage', [\App\Http\Controllers\Api\TradeOfferController::class, 'storeFromGarage']);

    // Favorite Routes
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites/{vehicleId}/toggle', [FavoriteController::class, 'toggle']);

    // Subscriptions
    Route::get('/subscriptions/my', [SubscriptionController::class, 'mySubscription']);
    Route::post('/subscriptions/checkout', [StripeController::class, 'createCheckout']);
    Route::post('/subscriptions/cancel', [SubscriptionController::class, 'cancel']);
});

// Stripe Webhook — must be public & exclude CSRF
Route::post('/webhooks/stripe', [StripeController::class, 'handleWebhook']);


// Admin Routes
// Admin Routes
Route::group(['prefix' => 'admin', 'middleware' => 'auth:sanctum'], function () {
    // Vehicle / Ad Management
    Route::get('/brands/{brandId}/models', [AdminVehicleController::class, 'getModelsByBrand']);
    Route::get('/vehicles', [AdminVehicleController::class, 'index']);
    Route::get('/vehicles/{id}', [AdminVehicleController::class, 'show']);
    Route::post('/vehicles', [AdminVehicleController::class, 'store']);
    Route::post('/vehicles/{id}', [AdminVehicleController::class, 'update']);
    Route::delete('/vehicles/{id}', [AdminVehicleController::class, 'destroy']);
    Route::patch('/vehicles/{id}/status', [AdminVehicleController::class, 'changeStatus']);
    Route::patch('/vehicles/{id}/featured', [AdminVehicleController::class, 'toggleFeatured']);

    // Role Management
    Route::middleware('role:admin')->group(
        function () {
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
        }
    );
});