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

            // 1. Pending Submissions Count
            try {
                $pendingSubmissionsCount = \App\Models\Submission::where('status', 'pending')->count();
            } catch (\Throwable $e) {
                $pendingSubmissionsCount = count(\App\Services\KumalaDataHub::getSubmissions('pending'));
            }
            $view->with('pendingSubmissionsCount', $pendingSubmissionsCount);

            // 2. Active Inquiries Count (Belum closing/selesai/batal)
            $closedStages = ['Closing', 'closing', 'Closing / Selesai', 'won', 'lost', 'batal', 'Batal', 'Selesai', 'selesai'];
            try {
                $activeInquiriesCount = \App\Models\Lead::whereNotIn('stage', $closedStages)->count();
            } catch (\Throwable $e) {
                $activeInquiriesCount = count(array_filter(\App\Services\KumalaDataHub::getInquiries(), function ($i) use ($closedStages) {
                    return !in_array($i['stage'] ?? '', $closedStages);
                }));
            }
            $view->with('activeInquiriesCount', $activeInquiriesCount);

            // 3. Upcoming Surveys Count
            try {
                $upcomingSurveysCount = \App\Models\Survey::whereDate('survey_date', '>=', now()->toDateString())->count();
                if ($upcomingSurveysCount === 0) {
                    $upcomingSurveysCount = \App\Models\Survey::count();
                }
            } catch (\Throwable $e) {
                $upcomingSurveysCount = count(\App\Services\KumalaDataHub::getSurveysToday());
            }
            $view->with('upcomingSurveysCount', $upcomingSurveysCount);

            // 4. Admin Total Notification Count
            $adminNotificationCount = $pendingSubmissionsCount + $activeInquiriesCount + $upcomingSurveysCount;
            $view->with('adminNotificationCount', $adminNotificationCount);
        });
    }
}
