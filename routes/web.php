<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\dashboard\AdminVehicleController;
use App\Http\Controllers\dashboard\VehicleRelationController;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\tables\Basic as TablesBasic;
use App\Http\Controllers\ecommerce\ProductList;
use App\Http\Controllers\ecommerce\AddProduct;
use App\Http\Controllers\ecommerce\CategoryList;
use App\Http\Controllers\ecommerce\Order;
use App\Http\Controllers\ecommerce\Customer;
use App\Http\Controllers\ecommerce\SettingsGeneral;
use App\Http\Controllers\ecommerce\SettingsPayment;
use App\Http\Controllers\ecommerce\SettingsShipping;
use App\Http\Controllers\ecommerce\SettingsTax;
use App\Http\Controllers\ecommerce\SettingsNotifications;
use App\Http\Controllers\subscription\SubscriptionList;
use App\Http\Controllers\subscription\SubscriptionCreate;
use App\Http\Controllers\subscription\SubscriptionPlans;
use App\Http\Controllers\subscription\SubscriptionPayments;
use App\Http\Controllers\users\User;
use App\Http\Controllers\users\UserAdd;
use App\Http\Controllers\Controller;
use APP\Http\Controllers\users\User_Controller;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;

// Main Page \Illuminate\Support\Facades\Route

// admin auth

Route::get('/', [AuthController::class , 'index'])->name('login');
Route::post('/login', [AuthController::class , 'store'])->name('admin-login-store');

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [Analytics::class , 'index'])->name('dashboard-analytics');
    Route::post('/logout', [AuthController::class , 'logout'])->name('logout');
    // layout
    Route::get('/layouts/without-menu', [WithoutMenu::class , 'index'])->name('layouts-without-menu');
    Route::get('/layouts/without-navbar', [WithoutNavbar::class , 'index'])->name('layouts-without-navbar');
    Route::get('/layouts/fluid', [Fluid::class , 'index'])->name('layouts-fluid');
    Route::get('/layouts/container', [Container::class , 'index'])->name('layouts-container');
    Route::get('/layouts/blank', [Blank::class , 'index'])->name('layouts-blank');

    // cards
    Route::get('/cards/basic', [CardBasic::class , 'index'])->name('cards-basic');

    // User Interface
    Route::get('/ui/accordion', [Accordion::class , 'index'])->name('ui-accordion');
    Route::get('/ui/alerts', [Alerts::class , 'index'])->name('ui-alerts');
    Route::get('/ui/badges', [Badges::class , 'index'])->name('ui-badges');
    Route::get('/ui/buttons', [Buttons::class , 'index'])->name('ui-buttons');
    Route::get('/ui/carousel', [Carousel::class , 'index'])->name('ui-carousel');
    Route::get('/ui/collapse', [Collapse::class , 'index'])->name('ui-collapse');
    Route::get('/ui/dropdowns', [Dropdowns::class , 'index'])->name('ui-dropdowns');
    Route::get('/ui/footer', [Footer::class , 'index'])->name('ui-footer');
    Route::get('/ui/list-groups', [ListGroups::class , 'index'])->name('ui-list-groups');
    Route::get('/ui/modals', [Modals::class , 'index'])->name('ui-modals');
    Route::get('/ui/navbar', [Navbar::class , 'index'])->name('ui-navbar');
    Route::get('/ui/offcanvas', [Offcanvas::class , 'index'])->name('ui-offcanvas');
    Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class , 'index'])->name('ui-pagination-breadcrumbs');
    Route::get('/ui/progress', [Progress::class , 'index'])->name('ui-progress');
    Route::get('/ui/spinners', [Spinners::class , 'index'])->name('ui-spinners');
    Route::get('/ui/tabs-pills', [TabsPills::class , 'index'])->name('ui-tabs-pills');
    Route::get('/ui/toasts', [Toasts::class , 'index'])->name('ui-toasts');
    Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class , 'index'])->name('ui-tooltips-popovers');
    Route::get('/ui/typography', [Typography::class , 'index'])->name('ui-typography');

    // extended ui
    Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class , 'index'])->name('extended-ui-perfect-scrollbar');
    Route::get('/extended/ui-text-divider', [TextDivider::class , 'index'])->name('extended-ui-text-divider');

    // icons
    Route::get('/icons/boxicons', [Boxicons::class , 'index'])->name('icons-boxicons');

    // form elements
    Route::get('/forms/basic-inputs', [BasicInput::class , 'index'])->name('forms-basic-inputs');
    Route::get('/forms/input-groups', [InputGroups::class , 'index'])->name('forms-input-groups');

    // form layouts
    Route::get('/form/layouts-vertical', [VerticalForm::class , 'index'])->name('form-layouts-vertical');
    Route::get('/form/layouts-horizontal', [HorizontalForm::class , 'index'])->name('form-layouts-horizontal');

    // tables
    Route::get('/tables/basic', [TablesBasic::class , 'index'])->name('tables-basic');

    // ecommerce
    Route::get('/app/ecommerce/products/product-list', [ProductList::class , 'index'])->name('app-ecommerce-product-list');
    Route::get('/app/ecommerce/products/add-product', [AddProduct::class , 'index'])->name('app-ecommerce-add-product');
    Route::get('/app/ecommerce/products/category-list', [CategoryList::class , 'index'])->name('app-ecommerce-category-list');
    Route::get('/app/ecommerce/order', [Order::class , 'index'])->name('app-ecommerce-order');
    Route::get('/app/ecommerce/customer', [Customer::class , 'index'])->name('app-ecommerce-customer');
    Route::get('/app/ecommerce/settings/general', [SettingsGeneral::class , 'index'])->name('app-ecommerce-settings-general');
    Route::get('/app/ecommerce/settings/payment', [SettingsPayment::class , 'index'])->name('app-ecommerce-settings-payment');
    Route::get('/app/ecommerce/settings/shipping', [SettingsShipping::class , 'index'])->name('app-ecommerce-settings-shipping');
    Route::get('/app/ecommerce/settings/tax', [SettingsTax::class , 'index'])->name('app-ecommerce-settings-tax');
    Route::get('/app/ecommerce/settings/notifications', [SettingsNotifications::class , 'index'])->name('app-ecommerce-settings-notifications');

    // subscription
    Route::get('/app/subscription/list', [SubscriptionList::class , 'index'])->name('app-subscription-list');
    Route::get('/app/subscription/create', [SubscriptionCreate::class , 'index'])->name('app-subscription-create');
    Route::get('/app/subscription/plans', [SubscriptionPlans::class , 'index'])->name('app-subscription-plans');
    Route::get('/app/subscription/payments', [SubscriptionPayments::class , 'index'])->name('app-subscription-payments');

    // Access Control (Roles & Users)
    Route::get('/app/access-control/roles', [AdminRoleController::class , 'index'])->name('admin.roles.index');
    Route::get('/app/access-control/roles/create', [AdminRoleController::class , 'create'])->name('admin.roles.create');
    Route::post('/app/access-control/roles', [AdminRoleController::class , 'store'])->name('admin.roles.store');
    Route::get('/app/access-control/roles/{id}/edit', [AdminRoleController::class , 'edit'])->name('admin.roles.edit');
    Route::put('/app/access-control/roles/{id}', [AdminRoleController::class , 'update'])->name('admin.roles.update');
    Route::delete('/app/access-control/roles/{id}', [AdminRoleController::class , 'destroy'])->name('admin.roles.destroy');

// Route::get('/app/access-control/users', [AdminUserController::class , 'index'])->name('admin.users.index');
// Route::get('/app/access-control/users/create', [AdminUserController::class , 'create'])->name('admin.users.create');
// Route::post('/app/access-control/users', [AdminUserController::class , 'store'])->name('admin.users.store');
// Route::get('/app/access-control/users/{id}/edit', [AdminUserController::class , 'edit'])->name('admin.users.edit');
// Route::put('/app/access-control/users/{id}', [AdminUserController::class , 'update'])->name('admin.users.update');
// Route::delete('/app/access-control/users/{id}', [AdminUserController::class , 'destroy'])->name('admin.users.destroy');
// // vehicles
// Route::get('/app/vehicles', [AdminVehicleController::class , 'index'])->name('admin.vehicles.index');
// Route::get('/app/vehicles/create', [AdminVehicleController::class , 'create'])->name('admin.vehicles.create');
// Route::post('/app/vehicles', [AdminVehicleController::class , 'store'])->name('admin.vehicles.store');
// Route::get('/app/vehicles/{id}/edit', [AdminVehicleController::class , 'edit'])->name('admin.vehicles.edit');
// Route::put('/app/vehicles/{id}', [AdminVehicleController::class , 'update'])->name('admin.vehicles.update');
// Route::delete('/app/vehicles/{id}', [AdminVehicleController::class , 'destroy'])->name('admin.vehicles.destroy');
// Route::get('/app/vehicles/models-by-brand/{brandId}', [AdminVehicleController::class , 'getModelsByBrand'])->name('admin.vehicles.models-by-brand');

// // settings
// Route::get('/app/vehicle-settings/{type}', [VehicleRelationController::class , 'index'])->name('admin.vehicle-settings.index');
// Route::post('/app/vehicle-settings/{type}', [VehicleRelationController::class , 'store'])->name('admin.vehicle-settings.store');
// Route::delete('/app/vehicle-settings/{type}/{id}', [VehicleRelationController::class , 'destroy'])->name('admin.vehicle-settings.destroy');
});
