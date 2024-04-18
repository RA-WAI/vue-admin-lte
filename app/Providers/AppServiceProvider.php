<?php

namespace App\Providers;

use App\Repositories\AppointmentRepository;
use App\Repositories\Interfaces\AppointmentRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AppointmentRepositoryInterface::class, AppointmentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::macro('toFormattedDate', function() {
            return $this->format('d-m-Y h:i A');
        });
    }
}
