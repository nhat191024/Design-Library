<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
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
        // Share categories to all views (so we can display them in the header)
        // only get 6 of them to avoid ugly/bad header
        View::share('shared_categories', Category::latest()->take(6)->get());
    }
}
