<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\Models\Page;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            'layouts.navigation',
            function ($view) {
                $pages = Cache::rememberForever('pages', function () {
                    return Page::whereActive(true)->orderBy('id', 'desc')->get();
                });
                $view->with('pages', $pages);
            }
        );
    }
}
