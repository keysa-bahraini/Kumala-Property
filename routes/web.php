<?php

use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Kumala Property (Public Web Portal)
|--------------------------------------------------------------------------
*/

// Halaman 1: Landing Page (Beranda)
Route::get('/', [PropertyController::class, 'home'])->name('home');

// Halaman 2: Explore Properti (Katalog & Filter)
Route::get('/explore', [PropertyController::class, 'explore'])->name('explore');

// Halaman 3: Detail Properti
Route::get('/properti/{slug}', [PropertyController::class, 'detail'])->name('property.detail');

// Halaman 4: Titip Properti (Consignment Submission Form)
Route::get('/titip-properti', [PropertyController::class, 'titipProperti'])->name('titip-properti');
Route::post('/titip-properti', [PropertyController::class, 'submitTitipProperti'])->name('titip-properti.submit');

// Halaman 5: Properti Favorit Saya
Route::get('/favorit', [PropertyController::class, 'favorites'])->name('favorites');

// Halaman 6: Tentang Kami & Kontak
Route::get('/tentang-kami', [PropertyController::class, 'about'])->name('about');

// Inquiry API for Detail Page Concierge
Route::post('/inquiry', [PropertyController::class, 'submitInquiry'])->name('inquiry.submit');

/*
|--------------------------------------------------------------------------
| Admin Suite Routes (Internal Control Portal)
|--------------------------------------------------------------------------
*/

// Halaman 7: Login Admin & Security
Route::get('/admin/login', [\App\Http\Controllers\AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\AdminController::class, 'authenticate'])->name('admin.authenticate');
Route::get('/admin/logout', [\App\Http\Controllers\AdminController::class, 'logoutRedirect'])->name('admin.logout');
Route::post('/admin/logout', [\App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout.post');

// Protected Admin Routes with 'admin' middleware
Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/properti', [\App\Http\Controllers\AdminController::class, 'properties'])->name('admin.properties');
    Route::get('/listing-masuk', [\App\Http\Controllers\AdminController::class, 'submissions'])->name('admin.submissions');
    Route::get('/riwayat-transaksi', [\App\Http\Controllers\AdminController::class, 'transactions'])->name('admin.transactions');
    Route::get('/inquiry', [\App\Http\Controllers\AdminController::class, 'inquiries'])->name('admin.inquiries');
    Route::get('/pengaturan', [\App\Http\Controllers\AdminController::class, 'settings'])->name('admin.settings');

    // Fase 5: Aksi Listing Masuk
    Route::post('/submissions/{id}/approve', [\App\Http\Controllers\AdminController::class, 'approveSubmission'])->name('admin.submissions.approve');
    Route::post('/submissions/{id}/reject', [\App\Http\Controllers\AdminController::class, 'rejectSubmission'])->name('admin.submissions.reject');
    Route::post('/submissions/{id}/revision', [\App\Http\Controllers\AdminController::class, 'revisionSubmission'])->name('admin.submissions.revision');
    Route::post('/submissions/{id}/checklist', [\App\Http\Controllers\AdminController::class, 'updateSubmissionChecklist'])->name('admin.submissions.checklist');
    Route::post('/submissions/{id}/update', [\App\Http\Controllers\AdminController::class, 'updateSubmissionData'])->name('admin.submissions.update');

    // Fase 5: Aksi Inquiry & CRM
    Route::post('/inquiry/{id}/stage', [\App\Http\Controllers\AdminController::class, 'updateLeadStage'])->name('admin.inquiry.stage');
    Route::post('/inquiry/{id}/assign', [\App\Http\Controllers\AdminController::class, 'assignLeadAgent'])->name('admin.inquiry.assign');
    Route::post('/inquiry/{id}/survey', [\App\Http\Controllers\AdminController::class, 'scheduleSurvey'])->name('admin.inquiry.survey');
    Route::post('/inquiry/manual', [\App\Http\Controllers\AdminController::class, 'storeManualLead'])->name('admin.inquiry.manual');

    // Fase 5: Aksi Riwayat Transaksi (Closing)
    Route::post('/riwayat-transaksi', [\App\Http\Controllers\AdminController::class, 'storeTransaction'])->name('admin.transactions.store');

    // Fase 6: CRUD Properti & Batch Actions
    Route::post('/properti/store', [\App\Http\Controllers\AdminController::class, 'storeProperty'])->name('admin.properties.store');
    Route::post('/properti/batch', [\App\Http\Controllers\AdminController::class, 'batchPropertyAction'])->name('admin.properties.batch');
    Route::post('/properti/{id}/update', [\App\Http\Controllers\AdminController::class, 'updateProperty'])->name('admin.properties.update');
    Route::post('/properti/{id}/status', [\App\Http\Controllers\AdminController::class, 'togglePropertyStatus'])->name('admin.properties.status');
    Route::post('/properti/{id}/delete', [\App\Http\Controllers\AdminController::class, 'deleteProperty'])->name('admin.properties.delete');

    // Fase 6: Pengaturan & Master Data
    Route::post('/pengaturan', [\App\Http\Controllers\AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::post('/pengaturan/kategori/toggle', [\App\Http\Controllers\AdminController::class, 'toggleCategory'])->name('admin.settings.category.toggle');
    Route::post('/pengaturan/kategori/store', [\App\Http\Controllers\AdminController::class, 'storeCategory'])->name('admin.settings.category.store');
    Route::post('/pengaturan/wilayah/toggle', [\App\Http\Controllers\AdminController::class, 'toggleRegion'])->name('admin.settings.region.toggle');
    Route::post('/pengaturan/wilayah/store', [\App\Http\Controllers\AdminController::class, 'storeRegion'])->name('admin.settings.region.store');

    // Fase 7: Global Search & Exports
    Route::get('/search', [\App\Http\Controllers\AdminController::class, 'search'])->name('admin.search');
    Route::get('/export/properties', [\App\Http\Controllers\AdminController::class, 'exportPropertiesCsv'])->name('admin.export.properties');
    Route::get('/export/daily-report', [\App\Http\Controllers\AdminController::class, 'exportDailyReportCsv'])->name('admin.export.daily');
    Route::get('/export/transactions', [\App\Http\Controllers\AdminController::class, 'exportTransactionsPdf'])->name('admin.export.transactions');
});

Route::get('/admin', function() {
    return redirect()->route('admin.dashboard');
});
