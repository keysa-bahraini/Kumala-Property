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

// Inquiry API for Detail Page Concierge
Route::post('/inquiry', [PropertyController::class, 'submitInquiry'])->name('inquiry.submit');
