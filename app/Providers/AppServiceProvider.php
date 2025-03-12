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
        // the try catch block is to prevent the error when the database is not yet migrated
        try {
            // Share categories to all views (so we can display them everywhere)
            $parentCategories = Category::where('is_show', 1)->whereNull('parent_id')->latest()->get();
            View::share('shared_categories', $parentCategories??[]);
        } catch (\Throwable $th) {
        }
    }
}
