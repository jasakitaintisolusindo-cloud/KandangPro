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
        \Illuminate\Pagination\Paginator::useTailwind();

        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            $pendingCount = \App\Models\DailyReport::where('status', 'draft')->count();
            $view->with('pendingReportsCount', $pendingCount);
        });
    }
}
