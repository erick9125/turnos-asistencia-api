<?php

use App\Jobs\Attendance\AssociateMarksJob;
use App\Jobs\Attendance\DailyAbsenceJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Configuración del scheduler
Schedule::job(new AssociateMarksJob())->everyFiveMinutes();
Schedule::job(new DailyAbsenceJob())->dailyAt('23:59');
