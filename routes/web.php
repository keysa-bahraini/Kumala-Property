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
Route::get('/admin/logout', [\App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout');
Route::post('/admin/logout', [\App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout.post');

// Halaman 8: Admin Operational Dashboard
Route::get('/admin/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');

// Halaman 9: Manajemen Properti (Master)
Route::get('/admin/properti', [\App\Http\Controllers\AdminController::class, 'properties'])->name('admin.properties');

// Halaman 10: Manajemen Listing Masuk (Gatekeeper Verification)
Route::get('/admin/listing-masuk', [\App\Http\Controllers\AdminController::class, 'submissions'])->name('admin.submissions');

// Halaman 11: Riwayat Terjual & Tersewa (Closing Archive)
Route::get('/admin/riwayat-transaksi', [\App\Http\Controllers\AdminController::class, 'transactions'])->name('admin.transactions');

// Halaman 12: Manajemen Data Inquiry (Lead Pipeline CRM)
Route::get('/admin/inquiry', [\App\Http\Controllers\AdminController::class, 'inquiries'])->name('admin.inquiries');

// Halaman 13: Pengaturan Sistem & Master Data
Route::get('/admin/pengaturan', [\App\Http\Controllers\AdminController::class, 'settings'])->name('admin.settings');

Route::get('/admin', function() {
    return redirect()->route('admin.dashboard');
});
