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
Route::get('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout.post');

// Halaman 8: Admin Operational Dashboard
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Halaman 9: Manajemen Properti (Master Inventory Hub)
Route::get('/admin/properti', [AdminController::class, 'properties'])->name('admin.properties');
Route::post('/admin/properti', [AdminController::class, 'storeProperty'])->name('admin.properties.store');
Route::post('/admin/properti/{id}/status', [AdminController::class, 'updatePropertyStatus'])->name('admin.properties.status');
Route::delete('/admin/properti/{id}', [AdminController::class, 'deleteProperty'])->name('admin.properties.delete');

// Halaman 10: Manajemen Listing Masuk (Gatekeeper Verification)
Route::get('/admin/listing-masuk', [AdminController::class, 'submissions'])->name('admin.submissions');
Route::post('/admin/listing-masuk/{id}/approve', [AdminController::class, 'approveSubmission'])->name('admin.submissions.approve');
Route::post('/admin/listing-masuk/{id}/status', [AdminController::class, 'updateSubmissionStatus'])->name('admin.submissions.status');

// Halaman 11: Riwayat Terjual & Tersewa (Closing Archive)
Route::get('/admin/riwayat-transaksi', [AdminController::class, 'transactions'])->name('admin.transactions');
Route::post('/admin/riwayat-transaksi', [AdminController::class, 'storeTransaction'])->name('admin.transactions.store');

// Halaman 12: Manajemen Data Inquiry (Lead Pipeline CRM)
Route::get('/admin/inquiry', [AdminController::class, 'inquiries'])->name('admin.inquiries');
Route::post('/admin/inquiry/{id}/stage', [AdminController::class, 'updateInquiryStage'])->name('admin.inquiries.stage');
Route::post('/admin/inquiry/survey', [AdminController::class, 'addSurvey'])->name('admin.inquiries.survey');

// Halaman 13: Pengaturan Sistem & Master Data
Route::get('/admin/pengaturan', [AdminController::class, 'settings'])->name('admin.settings');
Route::post('/admin/pengaturan', [AdminController::class, 'saveSettings'])->name('admin.settings.save');

Route::get('/admin', function() {
    return redirect()->route('admin.dashboard');
});
