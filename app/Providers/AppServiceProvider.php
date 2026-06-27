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
        $this->app->bind(
            \App\Domains\Equipment\Repositories\EquipmentRepositoryInterface::class,
            \App\Domains\Equipment\Repositories\EquipmentRepository::class
        );
        $this->app->bind(
            \App\Domains\Borrowing\Repositories\BorrowingRepositoryInterface::class,
            \App\Domains\Borrowing\Repositories\BorrowingRepository::class
        );
        $this->app->bind(
            \App\Domains\Waste\Repositories\WasteRepositoryInterface::class,
            \App\Domains\Waste\Repositories\WasteRepository::class
        );
        $this->app->bind(
            \App\Domains\AboutLab\Repositories\AboutLabRepositoryInterface::class,
            \App\Domains\AboutLab\Repositories\AboutLabRepository::class
        );
        $this->app->bind(
            \App\Domains\ActivityLog\Repositories\ActivityLogRepositoryInterface::class,
            \App\Domains\ActivityLog\Repositories\ActivityLogRepository::class
        );
        $this->app->bind(
            \App\Domains\Room\Repositories\RoomRepositoryInterface::class,
            \App\Domains\Room\Repositories\RoomRepository::class
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
