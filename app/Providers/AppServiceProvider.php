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
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $view->with('globalSettings', \App\Services\KumalaDataHub::getSettings());
            $view->with('pendingSubmissionsCount', count(\App\Services\KumalaDataHub::getSubmissions('pending')));
            $view->with('activeInquiriesCount', count(\App\Services\KumalaDataHub::getInquiries()));
        });
    }
}
