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
        $this->app->bind(
            \App\Domains\User\Repositories\UserRepositoryInterface::class,
            \App\Domains\User\Repositories\UserRepository::class
        );
        $this->app->bind(
            \App\Domains\Schedule\Repositories\ScheduleRepositoryInterface::class,
            \App\Domains\Schedule\Repositories\ScheduleRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
