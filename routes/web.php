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
use App\Http\Controllers\Backend\EmployeeController;
use App\Http\Controllers\Backend\DocumentCategoryController;
use App\Http\Controllers\Backend\DocumentController;
use App\Http\Controllers\Backend\AnnouncementController;
use App\Http\Controllers\Backend\IncidentReportController;
use App\Http\Controllers\Backend\CalendarEventController;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CesspoolController;
use App\Http\Controllers\Frontend\SepticController;
use App\Http\Controllers\Frontend\EmployeesController;

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


    // ==================== Career Portal — Employees ====================
    Route::get('employees',                 [EmployeeController::class, 'index'])->middleware('permission:employees.view')->name('admin.employees.index');
    Route::get('employees/create',          [EmployeeController::class, 'create'])->middleware('permission:employees.create')->name('admin.employees.create');
    Route::post('employees',                [EmployeeController::class, 'store'])->middleware('permission:employees.create')->name('admin.employees.store');
    Route::get('employees/{employee}/edit', [EmployeeController::class, 'edit'])->middleware('permission:employees.edit')->name('admin.employees.edit');
    Route::put('employees/{employee}',      [EmployeeController::class, 'update'])->middleware('permission:employees.edit')->name('admin.employees.update');
    Route::delete('employees/{employee}',   [EmployeeController::class, 'destroy'])->middleware('permission:employees.delete')->name('admin.employees.destroy');


    // ==================== Career Portal — Documents ====================

    // ---- Folders (document categories) ----
    Route::get('document-categories',                       [DocumentCategoryController::class, 'index'])->middleware('permission:document-categories.view')->name('admin.document-categories.index');
    Route::get('document-categories/create',                [DocumentCategoryController::class, 'create'])->middleware('permission:document-categories.create')->name('admin.document-categories.create');
    Route::post('document-categories',                      [DocumentCategoryController::class, 'store'])->middleware('permission:document-categories.create')->name('admin.document-categories.store');
    Route::get('document-categories/{document_category}/edit', [DocumentCategoryController::class, 'edit'])->middleware('permission:document-categories.edit')->name('admin.document-categories.edit');
    Route::put('document-categories/{document_category}',   [DocumentCategoryController::class, 'update'])->middleware('permission:document-categories.edit')->name('admin.document-categories.update');
    Route::delete('document-categories/{document_category}', [DocumentCategoryController::class, 'destroy'])->middleware('permission:document-categories.delete')->name('admin.document-categories.destroy');

    // ---- Documents (files) ----
    Route::get('documents',                 [DocumentController::class, 'index'])->middleware('permission:documents.view')->name('admin.documents.index');
    Route::get('documents/create',          [DocumentController::class, 'create'])->middleware('permission:documents.create')->name('admin.documents.create');
    Route::post('documents',                [DocumentController::class, 'store'])->middleware('permission:documents.create')->name('admin.documents.store');
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])->middleware('permission:documents.view')->name('admin.documents.download');
    Route::get('documents/{document}/edit', [DocumentController::class, 'edit'])->middleware('permission:documents.edit')->name('admin.documents.edit');
    Route::put('documents/{document}',      [DocumentController::class, 'update'])->middleware('permission:documents.edit')->name('admin.documents.update');
    Route::delete('documents/{document}',   [DocumentController::class, 'destroy'])->middleware('permission:documents.delete')->name('admin.documents.destroy');


    // ==================== Career Portal — Announcements ====================
    Route::get('announcements',                   [AnnouncementController::class, 'index'])->middleware('permission:announcements.view')->name('admin.announcements.index');
    Route::get('announcements/create',            [AnnouncementController::class, 'create'])->middleware('permission:announcements.create')->name('admin.announcements.create');
    Route::post('announcements',                  [AnnouncementController::class, 'store'])->middleware('permission:announcements.create')->name('admin.announcements.store');
    Route::get('announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->middleware('permission:announcements.edit')->name('admin.announcements.edit');
    Route::put('announcements/{announcement}',     [AnnouncementController::class, 'update'])->middleware('permission:announcements.edit')->name('admin.announcements.update');
    Route::delete('announcements/{announcement}',  [AnnouncementController::class, 'destroy'])->middleware('permission:announcements.delete')->name('admin.announcements.destroy');


    // ==================== Career Portal — Incident Reports ====================
    // view + create are available to employees (view is scoped to own in the controller);
    // edit/delete are admin-only.
    Route::get('incident-reports',                       [IncidentReportController::class, 'index'])->middleware('permission:incident-reports.view')->name('admin.incident-reports.index');
    Route::get('incident-reports/create',                [IncidentReportController::class, 'create'])->middleware('permission:incident-reports.create')->name('admin.incident-reports.create');
    Route::post('incident-reports',                      [IncidentReportController::class, 'store'])->middleware('permission:incident-reports.create')->name('admin.incident-reports.store');
    Route::get('incident-reports/{incident_report}',     [IncidentReportController::class, 'show'])->middleware('permission:incident-reports.view')->name('admin.incident-reports.show');
    Route::get('incident-reports/{incident_report}/edit',[IncidentReportController::class, 'edit'])->middleware('permission:incident-reports.edit')->name('admin.incident-reports.edit');
    Route::put('incident-reports/{incident_report}',     [IncidentReportController::class, 'update'])->middleware('permission:incident-reports.edit')->name('admin.incident-reports.update');
    Route::delete('incident-reports/{incident_report}',  [IncidentReportController::class, 'destroy'])->middleware('permission:incident-reports.delete')->name('admin.incident-reports.destroy');
    Route::delete('incident-report-photos/{photo}',      [IncidentReportController::class, 'destroyPhoto'])->middleware('permission:incident-reports.edit')->name('admin.incident-reports.photos.destroy');


    // ==================== Career Portal — Team Calendar ====================
    Route::get('calendar',                  [CalendarEventController::class, 'index'])->middleware('permission:calendar.view')->name('admin.calendar.index');
    Route::get('calendar/events',           [CalendarEventController::class, 'events'])->middleware('permission:calendar.view')->name('admin.calendar.events');
    Route::get('calendar/create',           [CalendarEventController::class, 'create'])->middleware('permission:calendar.create')->name('admin.calendar.create');
    Route::post('calendar',                 [CalendarEventController::class, 'store'])->middleware('permission:calendar.create')->name('admin.calendar.store');
    Route::get('calendar/{calendar}/edit',  [CalendarEventController::class, 'edit'])->middleware('permission:calendar.edit')->name('admin.calendar.edit');
    Route::put('calendar/{calendar}',       [CalendarEventController::class, 'update'])->middleware('permission:calendar.edit')->name('admin.calendar.update');
    Route::delete('calendar/{calendar}',    [CalendarEventController::class, 'destroy'])->middleware('permission:calendar.delete')->name('admin.calendar.destroy');
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


    Route::get('/employee-login', [EmployeesController::class, 'employee_login'])->name('frontend.employee_login');



});