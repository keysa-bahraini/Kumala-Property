<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Kumala Property RESTful API (v1)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // 1. Properties
    Route::get('/properties', [ApiController::class, 'properties']);
    Route::get('/properties/{slug}', [ApiController::class, 'propertyDetail']);

    // 2. Submissions (Titip Properti)
    Route::post('/titip-properti', [ApiController::class, 'submitTitipProperti']);

    // 3. Leads (Inquiries & Surveys)
    Route::post('/inquiry', [ApiController::class, 'submitInquiry']);

    // 4. Master Data
    Route::get('/categories', [ApiController::class, 'categories']);
    Route::get('/regions', [ApiController::class, 'regions']);

    // 5. Transactions Archive
    Route::get('/transactions', [ApiController::class, 'transactions']);

    // 6. Platform Stats
    Route::get('/stats', [ApiController::class, 'stats']);
});
