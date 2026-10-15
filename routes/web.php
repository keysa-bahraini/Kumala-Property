<?php

use App\Http\Controllers\AdminController;
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
Route::redirect('/about', '/tentang-kami');

// Inquiry API for Detail Page Concierge
Route::post('/inquiry', [PropertyController::class, 'submitInquiry'])->name('inquiry.submit');

/*
|--------------------------------------------------------------------------
| Admin Suite Routes (Internal Control Portal & Actions)
|--------------------------------------------------------------------------
*/

// Halaman 7: Login Admin & Security
Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');
Route::get('/admin/logout', [AdminController::class, 'logoutRedirect'])->name('admin.logout');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout.post');

// Protected Admin Routes with 'admin' middleware
Route::middleware(['admin'])->prefix('admin')->group(function () {
    // Halaman 8: Admin Operational Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Halaman 9: Manajemen Properti (Master Inventory Hub)
    Route::get('/properti', [AdminController::class, 'properties'])->name('admin.properties');
    Route::post('/properti', [AdminController::class, 'storeProperty']);
    Route::post('/properti/store', [AdminController::class, 'storeProperty'])->name('admin.properties.store');
    Route::post('/properti/batch', [AdminController::class, 'batchPropertyAction'])->name('admin.properties.batch');
    Route::post('/properti/{id}/update', [AdminController::class, 'updateProperty'])->name('admin.properties.update');
    Route::post('/properti/{id}/status', [AdminController::class, 'togglePropertyStatus'])->name('admin.properties.status');
    Route::delete('/properti/{id}', [AdminController::class, 'deleteProperty'])->name('admin.properties.delete');
    Route::post('/properti/{id}/delete', [AdminController::class, 'deleteProperty']);

    // Halaman 10: Manajemen Listing Masuk (Gatekeeper Verification)
    Route::get('/listing-masuk', [AdminController::class, 'submissions'])->name('admin.submissions');
    Route::post('/listing-masuk/{id}/approve', [AdminController::class, 'approveSubmission']);
    Route::post('/listing-masuk/{id}/status', [AdminController::class, 'updateSubmissionStatus'])->name('admin.submissions.status');
    Route::post('/submissions/{id}/approve', [AdminController::class, 'approveSubmission'])->name('admin.submissions.approve');
    Route::post('/submissions/{id}/reject', [AdminController::class, 'rejectSubmission'])->name('admin.submissions.reject');
    Route::post('/submissions/{id}/revision', [AdminController::class, 'revisionSubmission'])->name('admin.submissions.revision');
    Route::post('/submissions/{id}/checklist', [AdminController::class, 'updateSubmissionChecklist'])->name('admin.submissions.checklist');
    Route::post('/submissions/{id}/update', [AdminController::class, 'updateSubmissionData'])->name('admin.submissions.update');

    // Halaman 11: Riwayat Terjual & Tersewa (Closing Archive)
    Route::get('/riwayat-transaksi', [AdminController::class, 'transactions'])->name('admin.transactions');
    Route::post('/riwayat-transaksi', [AdminController::class, 'storeTransaction'])->name('admin.transactions.store');

    // Halaman 12: Manajemen Data Inquiry (Lead Pipeline CRM)
    Route::get('/inquiry', [AdminController::class, 'inquiries'])->name('admin.inquiries');
    Route::post('/inquiry/{id}/stage', [AdminController::class, 'updateLeadStage'])->name('admin.inquiry.stage');
    Route::post('/inquiry/{id}/assign', [AdminController::class, 'assignLeadAgent'])->name('admin.inquiry.assign');
    Route::post('/inquiry/{id}/survey', [AdminController::class, 'scheduleSurvey'])->name('admin.inquiry.survey');
    Route::post('/inquiry/survey', [AdminController::class, 'scheduleSurvey'])->name('admin.inquiries.survey');
    Route::post('/inquiry/manual', [AdminController::class, 'storeManualLead'])->name('admin.inquiry.manual');

    // Halaman 13: Pengaturan Sistem & Master Data
    Route::get('/pengaturan', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/pengaturan', [AdminController::class, 'updateSettings'])->name('admin.settings.save');
    Route::post('/pengaturan/kategori', [AdminController::class, 'storeCategory'])->name('admin.settings.category.store');
    Route::post('/pengaturan/kategori/toggle', [AdminController::class, 'toggleCategory'])->name('admin.settings.category.toggle');
    Route::post('/pengaturan/kategori/store', [AdminController::class, 'storeCategory']);
    Route::post('/pengaturan/wilayah/toggle', [AdminController::class, 'toggleRegion'])->name('admin.settings.region.toggle');
    Route::post('/pengaturan/wilayah/store', [AdminController::class, 'storeRegion'])->name('admin.settings.region.store');

    // Global Search & Exports
    Route::get('/search', [AdminController::class, 'search'])->name('admin.search');
    Route::get('/export/properties', [AdminController::class, 'exportPropertiesCsv'])->name('admin.export.properties');
    Route::get('/export/daily-report', [AdminController::class, 'exportDailyReportCsv'])->name('admin.export.daily');
    Route::get('/export/transactions', [AdminController::class, 'exportTransactionsPdf'])->name('admin.export.transactions');
});

Route::get('/admin', function() {
    return redirect()->route('admin.dashboard');
});
