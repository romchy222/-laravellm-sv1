<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Enrollment;
use App\Models\UserProgress;
use App\Observers\EnrollmentObserver;
use App\Observers\UserProgressObserver;

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
        // Register observers
        Enrollment::observe(EnrollmentObserver::class);
        UserProgress::observe(UserProgressObserver::class);
    }
}
