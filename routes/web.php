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
use App\Http\Controllers\users\User_Controller;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\PartnerController as AdminPartnerController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;


// Admin Authentication (Publicly accessible but handled by guard)
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('admin-login-store');

// Protected Routes Section
Route::middleware(['auth:admin-guard', 'role:super-admin|admin|sub-admin,admin-guard'])->group(function () {

    // General Dashboard & Logout
    Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard-analytics');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin Profile
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('admin.profile.index');
    Route::post('/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::post('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.update-password');

    // Layout, UI, & Forms (Accessible Utility Pages for all authorized staff)
    Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
    Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
    Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
    Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
    Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');
    Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');
    Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
    Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
    Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
    Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
    Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
    Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
    Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
    Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
    Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
    Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
    Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
    Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
    Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
    Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
    Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
    Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
    Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
    Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
    Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');
    Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
    Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');
    Route::get('/icons/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');
    Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
    Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');
    Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
    Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');
    Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');

    // E-commerce Products Module
    Route::middleware(['permission:view-products,admin-guard'])->group(function () {
        Route::get('/app/ecommerce/products/product-list', [ProductList::class, 'index'])->name('app-ecommerce-product-list');
        Route::get('/app/ecommerce/products/add-product', [AddProduct::class, 'index'])->name('app-ecommerce-add-product');
        Route::get('/app/ecommerce/products/category-list', [CategoryList::class, 'index'])->name('app-ecommerce-category-list');
    });

    // E-commerce Orders Module
    Route::middleware(['permission:view-orders,admin-guard'])->group(function () {
        Route::get('/app/ecommerce/order', [Order::class, 'index'])->name('app-ecommerce-order');
    });

    // E-commerce Customers Module
    Route::middleware(['permission:view-customers,admin-guard'])->group(function () {
        Route::get('/app/ecommerce/customer', [Customer::class, 'index'])->name('app-ecommerce-customer');
    });

    // Main Settings Module
    Route::middleware(['permission:view-settings,admin-guard'])->group(function () {
        Route::get('/app/ecommerce/settings/general', [SettingsGeneral::class, 'index'])->name('app-ecommerce-settings-general');
        Route::get('/app/ecommerce/settings/payment', [SettingsPayment::class, 'index'])->name('app-ecommerce-settings-payment');
        Route::get('/app/ecommerce/settings/shipping', [SettingsShipping::class, 'index'])->name('app-ecommerce-settings-shipping');
        Route::get('/app/ecommerce/settings/tax', [SettingsTax::class, 'index'])->name('app-ecommerce-settings-tax');
        Route::get('/app/ecommerce/settings/notifications', [SettingsNotifications::class, 'index'])->name('app-ecommerce-settings-notifications');
        Route::get('/app/ecommerce/settings/header-footer', [\App\Http\Controllers\ecommerce\SettingsHeaderFooter::class, 'index'])->name('app-ecommerce-settings-header-footer');

        // Settings write/edit operations
        Route::post('/app/ecommerce/settings/general', [SettingsGeneral::class, 'store'])->name('app-ecommerce-settings-general-store')->middleware('permission:edit-settings,admin-guard');
        Route::post('/app/ecommerce/settings/payment', [SettingsPayment::class, 'store'])->name('app-ecommerce-settings-payment-store')->middleware('permission:edit-settings,admin-guard');
        Route::post('/app/ecommerce/settings/header-footer', [\App\Http\Controllers\ecommerce\SettingsHeaderFooter::class, 'store'])->name('app-ecommerce-settings-header-footer-store')->middleware('permission:edit-settings,admin-guard');
        Route::post('/app/ecommerce/settings/notifications', [SettingsNotifications::class, 'store'])->name('app-ecommerce-settings-notifications-store')->middleware('permission:edit-settings,admin-guard');
        Route::post('/app/ecommerce/settings/notifications/test', [SettingsNotifications::class, 'testConnectivity'])->name('app-ecommerce-settings-notifications-test')->middleware('permission:edit-settings,admin-guard');
    });

    // Subscription Management
    Route::middleware(['permission:view-subscriptions,admin-guard'])->group(function () {
        Route::get('/app/subscription/list', [SubscriptionList::class, 'index'])->name('app-subscription-list');
        Route::get('/app/subscription/plans', [SubscriptionPlans::class, 'index'])->name('app-subscription-plans');
        Route::patch('/app/subscription/plans/{id}/status', [SubscriptionPlans::class, 'updateStatus'])->name('app-subscription-plans-status');
        Route::put('/app/subscription/plans/{id}/update', [SubscriptionPlans::class, 'update'])->name('app-subscription-plans-update');
        Route::delete('/app/subscription/plans/{id}', [SubscriptionPlans::class, 'destroy'])->name('app-subscription-plans-destroy');
        Route::get('/app/subscription/payments', [SubscriptionPayments::class, 'index'])->name('app-subscription-payments');

        Route::get('/app/subscription/view/{id}', [SubscriptionList::class, 'show'])->name('app-subscription-view');
        Route::patch('/app/subscription/{id}/status', [SubscriptionList::class, 'updateStatus'])->name('app-subscription-status');
        Route::put('/app/subscription/{id}/update', [SubscriptionList::class, 'update'])->name('app-subscription-update');

        // Subscription creation
        Route::get('/app/subscription/create', [SubscriptionCreate::class, 'index'])->name('app-subscription-create')->middleware('permission:create-subscriptions,admin-guard');
        Route::post('/app/subscription/create', [SubscriptionCreate::class, 'store'])->name('app-subscription-store')->middleware('permission:create-subscriptions,admin-guard');

        // Subscription edit (full-page)
        Route::get('/app/subscription/plans/{id}/edit', [SubscriptionCreate::class, 'edit'])->name('app-subscription-plan-edit')->middleware('permission:edit-subscriptions,admin-guard');
        Route::put('/app/subscription/plans/{id}', [SubscriptionCreate::class, 'update'])->name('app-subscription-plan-update')->middleware('permission:edit-subscriptions,admin-guard');
    });

    // Access Control: ROLES
    Route::middleware(['permission:view-roles,admin-guard'])->group(function () {
        Route::get('/app/access-control/roles', [AdminRoleController::class, 'index'])->name('admin.roles.index');
        Route::get('/app/access-control/roles/create', [AdminRoleController::class, 'create'])->name('admin.roles.create')->middleware('permission:create-roles,admin-guard');
        Route::post('/app/access-control/roles', [AdminRoleController::class, 'store'])->name('admin.roles.store')->middleware('permission:create-roles,admin-guard');
        Route::get('/app/access-control/roles/{id}/edit', [AdminRoleController::class, 'edit'])->name('admin.roles.edit')->middleware('permission:edit-roles,admin-guard');
        Route::put('/app/access-control/roles/{id}', [AdminRoleController::class, 'update'])->name('admin.roles.update')->middleware('permission:edit-roles,admin-guard');
        Route::delete('/app/access-control/roles/{id}', [AdminRoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware('permission:delete-roles,admin-guard');
    });

    // Access Control: ADMIN USERS
    Route::middleware(['permission:view-users,admin-guard'])->group(function () {
        Route::get('/app/access-control/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/app/access-control/users/create', [AdminUserController::class, 'create'])->name('admin.users.create')->middleware('permission:create-users,admin-guard');
        Route::post('/app/access-control/users', [AdminUserController::class, 'store'])->name('admin.users.store')->middleware('permission:create-users,admin-guard');
        Route::get('/app/access-control/users/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit')->middleware('permission:edit-users,admin-guard');
        Route::put('/app/access-control/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update')->middleware('permission:edit-users,admin-guard');
        Route::delete('/app/access-control/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy')->middleware('permission:delete-users,admin-guard');
    });

    // Access Control: WEB USERS
    Route::middleware(['permission:view-users,admin-guard'])->group(function () {
        Route::get('/app/users', [User_Controller::class, 'index'])->name('admin.web-users.index');
        Route::get('/app/users/{id}', [User_Controller::class, 'view'])->name('admin.web-users.view');
    });

    // Main VEHICLES Module
    Route::middleware(['permission:view-vehicles,admin-guard'])->group(function () {
        Route::get('/app/vehicles', [AdminVehicleController::class, 'index'])->name('admin.vehicles.index');
        Route::get('/app/vehicles/create', [AdminVehicleController::class, 'create'])->name('admin.vehicles.create')->middleware('permission:create-vehicles,admin-guard');
        Route::get('/app/vehicles/{id}', [AdminVehicleController::class, 'show'])->name('admin.vehicles.show');
        Route::patch('/app/vehicles/{vehicle}/toggle-featured', [AdminVehicleController::class, 'toggleFeatured'])->name('admin.vehicles.toggle-featured');
        Route::get('/app/get-models-by-brand/{brandId}', [AdminVehicleController::class, 'getModelsByBrand'])->name('admin.vehicles.models-by-brand');

        // Granular vehicle permissions for modification
        Route::post('/app/vehicles', [AdminVehicleController::class, 'store'])->name('admin.vehicles.store')->middleware('permission:create-vehicles,admin-guard');
        Route::get('/app/vehicles/{id}/edit', [AdminVehicleController::class, 'edit'])->name('admin.vehicles.edit')->middleware('permission:edit-vehicles,admin-guard');
        Route::put('/app/vehicles/{id}', [AdminVehicleController::class, 'update'])->name('admin.vehicles.update')->middleware('permission:edit-vehicles,admin-guard');
        Route::delete('/app/vehicles/{id}', [AdminVehicleController::class, 'destroy'])->name('admin.vehicles.destroy')->middleware('permission:delete-vehicles,admin-guard');
        Route::patch('/app/vehicles/{id}/status', [AdminVehicleController::class, 'updateStatus'])->name('admin.vehicles.update-status')->middleware('permission:edit-vehicles,admin-guard');
    });

    // PARTNERS Module
    Route::middleware(['permission:view-partners,admin-guard'])->group(function () {
        Route::get('/app/partners', [AdminPartnerController::class, 'index'])->name('admin.partners.index');
        Route::get('/app/partners/{partner}', [AdminPartnerController::class, 'show'])->name('admin.partners.show');
        Route::get('/app/partners/create', [AdminPartnerController::class, 'create'])->name('admin.partners.create')->middleware('permission:create-partners,admin-guard');
        Route::post('/app/partners', [AdminPartnerController::class, 'store'])->name('admin.partners.store')->middleware('permission:create-partners,admin-guard');
        Route::get('/app/partners/{partner}/edit', [AdminPartnerController::class, 'edit'])->name('admin.partners.edit')->middleware('permission:edit-partners,admin-guard');
        Route::put('/app/partners/{partner}', [AdminPartnerController::class, 'update'])->name('admin.partners.update')->middleware('permission:edit-partners,admin-guard');
        Route::delete('/app/partners/{partner}', [AdminPartnerController::class, 'destroy'])->name('admin.partners.destroy')->middleware('permission:delete-partners,admin-guard');
        Route::patch('/app/partners/{partner}/toggle-status', [AdminPartnerController::class, 'toggleStatus'])->name('admin.partners.toggle-status')->middleware('permission:edit-partners,admin-guard');
    });

    // CAR / VEHICLE SETTINGS Module
    Route::middleware(['permission:view-car_settings,admin-guard'])->group(function () {
        Route::get('/app/vehicle-settings/{type}', [VehicleRelationController::class, 'index'])->name('admin.vehicle-settings.index');
        Route::post('/app/vehicle-settings/{type}', [VehicleRelationController::class, 'store'])->name('admin.vehicle-settings.store')->middleware('permission:create-car_settings,admin-guard');
        Route::put('/app/vehicle-settings/{type}/{id}', [VehicleRelationController::class, 'update'])->name('admin.vehicle-settings.update')->middleware('permission:edit-car_settings,admin-guard');
        Route::patch('/app/vehicle-settings/{type}/{id}/toggle-status', [VehicleRelationController::class, 'toggleStatus'])->name('admin.vehicle-settings.toggle-status')->middleware('permission:edit-car_settings,admin-guard');
        Route::delete('/app/vehicle-settings/{type}/{id}', [VehicleRelationController::class, 'destroy'])->name('admin.vehicle-settings.destroy')->middleware('permission:delete-car_settings,admin-guard');
    });

    // EMAIL TEMPLATES Module
    Route::middleware(['permission:view-email_templates,admin-guard'])->group(function () {
        Route::get('/app/email-templates', [EmailTemplateController::class, 'index'])->name('admin.email-templates.index');
        Route::put('/app/email-templates/{id}', [EmailTemplateController::class, 'update'])->name('admin.email-templates.update')->middleware('permission:edit-email_templates,admin-guard');
        Route::post('/app/email-templates/settings', [EmailTemplateController::class, 'updateEditorSettings'])->name('admin.email-templates.settings.update')->middleware('permission:edit-email_templates,admin-guard');
    });
    // CONTACTS Module
    Route::group(['prefix' => 'app/contacts'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('admin.contacts.index');
        Route::get('/{id}', [\App\Http\Controllers\Admin\ContactController::class, 'show'])->name('admin.contacts.show');
        Route::patch('/{id}/status', [\App\Http\Controllers\Admin\ContactController::class, 'updateStatus'])->name('admin.contacts.update-status');
        Route::post('/{id}/reply', [\App\Http\Controllers\Admin\ContactController::class, 'sendReply'])->name('admin.contacts.reply');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('admin.contacts.destroy');
    });

    // CMS Module
    Route::group(['prefix' => 'app/cms'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\CMSController::class, 'index'])->name('admin.cms.index');
        Route::get('/create', [\App\Http\Controllers\Admin\CMSController::class, 'create'])->name('admin.cms.create');
        Route::post('/', [\App\Http\Controllers\Admin\CMSController::class, 'store'])->name('admin.cms.store');
        Route::get('/{id}/edit', [\App\Http\Controllers\Admin\CMSController::class, 'edit'])->name('admin.cms.edit');
        Route::put('/{id}', [\App\Http\Controllers\Admin\CMSController::class, 'update'])->name('admin.cms.update');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\CMSController::class, 'destroy'])->name('admin.cms.destroy');

        // CMS Items Management (within a section)
        Route::post('/{sectionId}/items', [\App\Http\Controllers\Admin\CMSController::class, 'storeItem'])->name('admin.cms.items.store');
        Route::post('/items/update-direct', [\App\Http\Controllers\Admin\CMSController::class, 'updateItemDirect'])->name('admin.cms.items.update-direct');
        Route::put('/items/{itemId}', [\App\Http\Controllers\Admin\CMSController::class, 'updateItem'])->name('admin.cms.items.update');
        Route::delete('/items/{itemId}', [\App\Http\Controllers\Admin\CMSController::class, 'destroyItem'])->name('admin.cms.items.destroy');
    });

    // TRADE OFFERS Module
    Route::group(['prefix' => 'app/trade-offers'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\TradeOfferController::class, 'index'])->name('admin.trade-offers.index');
        Route::get('/{id}', [\App\Http\Controllers\Admin\TradeOfferController::class, 'show'])->name('admin.trade-offers.show');
        Route::patch('/{id}/status', [\App\Http\Controllers\Admin\TradeOfferController::class, 'updateStatus'])->name('admin.trade-offers.update-status');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\TradeOfferController::class, 'destroy'])->name('admin.trade-offers.destroy');
    });

    // NEWSLETTER Module
    Route::group(['prefix' => 'app/newsletter'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('admin.newsletter.index');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('admin.newsletter.destroy');
    });

});
