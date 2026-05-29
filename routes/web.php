<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

use App\Http\Controllers\Backend\DisposalController;
use App\Http\Controllers\Backend\EmailSettingsController;
use App\Http\Controllers\Backend\CesspoolRecordsController;
use App\Http\Controllers\Backend\SepticRecordsController;

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
        })->name('admin.dashboard');
});


// ==== Manage Disposal Details
Route::get('manage-disposal-details/export', [DisposalController::class, 'export'])->name('manage-disposal-details.export');
Route::post('manage-disposal-details/export-selected-pdf',[DisposalController::class, 'exportSelectedPdf'])->name('manage-disposal-details.exportSelectedPdf');

Route::post('/generate-monthly-report', [DisposalController::class, 'generate_monthly_report'])->name('generate.monthly.report');
Route::resource('manage-disposal-details', DisposalController::class);


Route::resource('manage-email-settings', EmailSettingsController::class);


// ==== Cesspool Records
Route::get('cesspool-records',                  [CesspoolRecordsController::class, 'index'])->name('cesspool-records.index');
Route::get('cesspool-records/{id}/edit',        [CesspoolRecordsController::class, 'edit'])->name('cesspool-records.edit');
Route::put('cesspool-records/{id}',             [CesspoolRecordsController::class, 'update'])->name('cesspool-records.update');
Route::get('cesspool-records/{id}/pdf',         [CesspoolRecordsController::class, 'exportPdf'])->name('cesspool-records.pdf');
Route::post('cesspool-records/send-report',     [CesspoolRecordsController::class, 'sendReport'])->name('cesspool-records.send-report');
Route::delete('cesspool-records/{id}',          [CesspoolRecordsController::class, 'destroy'])->name('cesspool-records.destroy');

// ==== Septic Records
Route::get('septic-records',                    [SepticRecordsController::class, 'index'])->name('septic-records.index');
Route::get('septic-records/{id}/edit',          [SepticRecordsController::class, 'edit'])->name('septic-records.edit');
Route::put('septic-records/{id}',               [SepticRecordsController::class, 'update'])->name('septic-records.update');
Route::get('septic-records/{id}/pdf',           [SepticRecordsController::class, 'exportPdf'])->name('septic-records.pdf');
Route::post('septic-records/send-report',       [SepticRecordsController::class, 'sendReport'])->name('septic-records.send-report');
Route::delete('septic-records/{id}',            [SepticRecordsController::class, 'destroy'])->name('septic-records.destroy');




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