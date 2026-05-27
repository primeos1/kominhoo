<?php

namespace App\Providers;

use App\Support\CmsContent;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CmsContent::class, fn () => new CmsContent());
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $cms = app(CmsContent::class)->all();

            $view->with('siteContent', $cms['content'] ?? []);
            $view->with('quizContent', $cms['quiz'] ?? []);
        });
    }
}
