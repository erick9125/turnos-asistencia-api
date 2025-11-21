<?php

namespace App\Providers;

use App\Application\Attendance\AbsenceService;
use App\Application\Devices\DeviceService;
use App\Application\Export\ExportService;
use App\Application\Marks\MarkAssociationService;
use App\Application\Marks\MarkIngestionService;
use App\Application\Reports\ReportService;
use App\Application\Shifts\ShiftService;
use App\Application\Users\UserService;
use App\Application\Workers\WorkerService;
use App\Infrastructure\Persistence\Repositories\DeviceRepository;
use App\Infrastructure\Persistence\Repositories\MarkRepository;
use App\Infrastructure\Persistence\Repositories\ShiftRepository;
use App\Infrastructure\Persistence\Repositories\UserRepository;
use App\Infrastructure\Persistence\Repositories\WorkerRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registro de repositorios
        $this->app->singleton(ShiftRepository::class);
        $this->app->singleton(MarkRepository::class);
        $this->app->singleton(WorkerRepository::class);
        $this->app->singleton(DeviceRepository::class);
        $this->app->singleton(UserRepository::class);

        // Registro de servicios
        $this->app->singleton(ShiftService::class);
        $this->app->singleton(MarkIngestionService::class);
        $this->app->singleton(MarkAssociationService::class);
        $this->app->singleton(WorkerService::class);
        $this->app->singleton(DeviceService::class);
        $this->app->singleton(AbsenceService::class);
        $this->app->singleton(ReportService::class);
        $this->app->singleton(ExportService::class);
        $this->app->singleton(UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
