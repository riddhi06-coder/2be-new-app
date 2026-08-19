<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

use App\Http\Controllers\Backend\DisposalController;
use App\Http\Controllers\Backend\EmailSettingsController;
use App\Http\Controllers\Backend\CesspoolRecordsController;
use App\Http\Controllers\Backend\SepticRecordsController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\PermissionController;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CesspoolController;
use App\Http\Controllers\Frontend\SepticController;


// =========================================================================== Backend Routes


// Authentication Routes
Route::get('/login', [LoginController::class, 'login'])->name('admin.login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('admin.authenticate');
Route::get('/logout', [LoginController::class, 'logout'])->name('admin.logout');
Route::get('/change-password', [LoginController::class, 'change_password'])->name('admin.changepassword');
Route::post('/update-password', [LoginController::class, 'updatePassword'])->name('admin.updatepassword');

Route::get('/register', [LoginController::class, 'register'])->name('admin.register');
Route::post('/register', [LoginController::class, 'authenticate_register'])->name('admin.register.authenticate');
    
// Admin Routes with Middleware
Route::group(['middleware' => ['auth:web', \App\Http\Middleware\PreventBackHistoryMiddleware::class]], function () {
        Route::get('/dashboard', function () {
            return view('backend.dashboard');
        })->middleware('permission:dashboard.view')->name('admin.dashboard');

    // ==== Manage Disposal Details
    Route::get('manage-disposal-details/export', [DisposalController::class, 'export'])->middleware('permission:manage-disposal-details.view')->name('manage-disposal-details.export');
    Route::post('manage-disposal-details/export-selected-pdf',[DisposalController::class, 'exportSelectedPdf'])->middleware('permission:manage-disposal-details.view')->name('manage-disposal-details.exportSelectedPdf');

    Route::post('/generate-monthly-report', [DisposalController::class, 'generate_monthly_report'])->middleware('permission:manage-disposal-details.view')->name('generate.monthly.report');
    Route::resource('manage-disposal-details', DisposalController::class)->only(['index', 'show'])->middleware('permission:manage-disposal-details.view');
    Route::resource('manage-disposal-details', DisposalController::class)->only(['create', 'store'])->middleware('permission:manage-disposal-details.create');
    Route::resource('manage-disposal-details', DisposalController::class)->only(['edit', 'update'])->middleware('permission:manage-disposal-details.edit');
    Route::resource('manage-disposal-details', DisposalController::class)->only(['destroy'])->middleware('permission:manage-disposal-details.delete');


    // ==== Email Settings
    Route::resource('manage-email-settings', EmailSettingsController::class)->only(['index', 'show'])->middleware('permission:manage-email-settings.view');
    Route::resource('manage-email-settings', EmailSettingsController::class)->only(['create', 'store'])->middleware('permission:manage-email-settings.create');
    Route::resource('manage-email-settings', EmailSettingsController::class)->only(['edit', 'update'])->middleware('permission:manage-email-settings.edit');
    Route::resource('manage-email-settings', EmailSettingsController::class)->only(['destroy'])->middleware('permission:manage-email-settings.delete');


    // ==== Cesspool Records
    Route::get('cesspool-records',                  [CesspoolRecordsController::class, 'index'])->middleware('permission:cesspool-records.view')->name('cesspool-records.index');
    Route::get('cesspool-records/{id}/pdf',         [CesspoolRecordsController::class, 'exportPdf'])->middleware('permission:cesspool-records.view')->name('cesspool-records.pdf');
    Route::post('cesspool-records/send-report',     [CesspoolRecordsController::class, 'sendReport'])->middleware('permission:cesspool-records.view')->name('cesspool-records.send-report');
    Route::get('cesspool-records/{id}/edit',        [CesspoolRecordsController::class, 'edit'])->middleware('permission:cesspool-records.edit')->name('cesspool-records.edit');
    Route::put('cesspool-records/{id}',             [CesspoolRecordsController::class, 'update'])->middleware('permission:cesspool-records.edit')->name('cesspool-records.update');
    Route::delete('cesspool-records/{id}',          [CesspoolRecordsController::class, 'destroy'])->middleware('permission:cesspool-records.delete')->name('cesspool-records.destroy');

    // ==== Septic Records
    Route::get('septic-records',                    [SepticRecordsController::class, 'index'])->middleware('permission:septic-records.view')->name('septic-records.index');
    Route::get('septic-records/{id}/pdf',           [SepticRecordsController::class, 'exportPdf'])->middleware('permission:septic-records.view')->name('septic-records.pdf');
    Route::post('septic-records/send-report',       [SepticRecordsController::class, 'sendReport'])->middleware('permission:septic-records.view')->name('septic-records.send-report');
    Route::get('septic-records/{id}/edit',          [SepticRecordsController::class, 'edit'])->middleware('permission:septic-records.edit')->name('septic-records.edit');
    Route::put('septic-records/{id}',               [SepticRecordsController::class, 'update'])->middleware('permission:septic-records.edit')->name('septic-records.update');
    Route::delete('septic-records/{id}',            [SepticRecordsController::class, 'destroy'])->middleware('permission:septic-records.delete')->name('septic-records.destroy');


    // ==================== User Management (Roles / Users / Permissions) ====================

    // ---- Roles ----
    Route::get('roles',                [RoleController::class, 'index'])->middleware('permission:roles.view')->name('admin.roles.index');
    Route::get('roles/create',         [RoleController::class, 'create'])->middleware('permission:roles.create')->name('admin.roles.create');
    Route::post('roles',               [RoleController::class, 'store'])->middleware('permission:roles.create')->name('admin.roles.store');
    Route::get('roles/{role}/edit',    [RoleController::class, 'edit'])->middleware('permission:roles.edit')->name('admin.roles.edit');
    Route::put('roles/{role}',         [RoleController::class, 'update'])->middleware('permission:roles.edit')->name('admin.roles.update');
    Route::delete('roles/{role}',      [RoleController::class, 'destroy'])->middleware('permission:roles.delete')->name('admin.roles.destroy');

    // ---- Users ----
    Route::get('users',                [UserController::class, 'index'])->middleware('permission:users.view')->name('admin.users.index');
    Route::get('users/create',         [UserController::class, 'create'])->middleware('permission:users.create')->name('admin.users.create');
    Route::post('users',               [UserController::class, 'store'])->middleware('permission:users.create')->name('admin.users.store');
    Route::get('users/{user}/edit',    [UserController::class, 'edit'])->middleware('permission:users.edit')->name('admin.users.edit');
    Route::put('users/{user}',         [UserController::class, 'update'])->middleware('permission:users.edit')->name('admin.users.update');
    Route::delete('users/{user}',      [UserController::class, 'destroy'])->middleware('permission:users.delete')->name('admin.users.destroy');

    // ---- Permissions (per-role matrix) ----
    Route::get('permissions',                  [PermissionController::class, 'index'])->middleware('permission:permissions.view')->name('admin.permissions.index');
    Route::get('permissions/{role}/edit',      [PermissionController::class, 'edit'])->middleware('permission:permissions.assign')->name('admin.permissions.edit');
    Route::put('permissions/{role}',           [PermissionController::class, 'update'])->middleware('permission:permissions.assign')->name('admin.permissions.update');

    // ---- Permission catalog (add new permissions when new tabs appear) ----
    Route::get('permissions-catalog',                          [PermissionController::class, 'manage'])->middleware('permission:permissions.assign')->name('admin.permissions.manage');
    Route::get('permissions-catalog/create',                   [PermissionController::class, 'createPermission'])->middleware('permission:permissions.assign')->name('admin.permissions.manage.create');
    Route::post('permissions-catalog',                         [PermissionController::class, 'storePermission'])->middleware('permission:permissions.assign')->name('admin.permissions.manage.store');
    Route::get('permissions-catalog/{permission}/edit',        [PermissionController::class, 'editPermission'])->middleware('permission:permissions.assign')->name('admin.permissions.manage.edit');
    Route::put('permissions-catalog/{permission}',             [PermissionController::class, 'updatePermission'])->middleware('permission:permissions.assign')->name('admin.permissions.manage.update');
    Route::delete('permissions-catalog/{permission}',          [PermissionController::class, 'destroyPermission'])->middleware('permission:permissions.assign')->name('admin.permissions.manage.destroy');
});




// // =========================================================================== Frontend Routes

Route::group(['prefix'=> '', 'middleware'=>[\App\Http\Middleware\PreventBackHistoryMiddleware::class]],function(){

    // ==== Home
    Route::get('/', [HomeController::class, 'home'])->name('frontend.index');
    Route::get('/log-waste-disposal', [HomeController::class, 'log_waste_disposal'])->name('frontend.log_waste_disposal');
    Route::get('/thank-you', [HomeController::class, 'thank_you'])->name('frontend.thank_you');
    Route::post('/waste-entry', [HomeController::class, 'store_waste_entry'])->name('waste.store');


    Route::get('/cesspool-systems', [CesspoolController::class, 'cesspool_systems'])->name('frontend.cesspool_systems');
    Route::post('/cesspool-systems', [CesspoolController::class, 'store_cesspool'])->name('cesspool.store');
    Route::post('/cesspool-systems/draft', [CesspoolController::class, 'save_draft'])->name('cesspool.draft');

    
    Route::get('/septic-systems', [SepticController::class, 'septic_systems'])->name('frontend.septic_systems');
    Route::post('/septic-systems', [SepticController::class, 'store_septic'])->name('septic.store');
    Route::post('/septic-systems/draft', [SepticController::class, 'save_draft'])->name('septic.draft');    
});