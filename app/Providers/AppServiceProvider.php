<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Global Settings untuk semua view
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $view->with('globalSettings', \App\Services\KumalaDataHub::getSettings());
        });

        // 2. Notifikasi Operasional Admin (Dibatasi hanya untuk view admin demi performa)
        \Illuminate\Support\Facades\View::composer(['admin.*', 'layouts.admin'], function ($view) {
            $metrics = \App\Services\KumalaDataHub::getAdminNotificationMetrics();

            $view->with('pendingSubmissionsCount', $metrics['pending_submissions']);
            $view->with('newInquiriesCount', $metrics['new_inquiries']);
            $view->with('activeInquiriesCount', $metrics['new_inquiries']);
            $view->with('upcomingSurveysCount', $metrics['upcoming_surveys']);
            $view->with('adminNotificationCount', $metrics['total']);
        });
    }
}
