<?php

namespace App\Jobs\Attendance;

use App\Application\Attendance\AbsenceService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * Job para procesar ausencias diarias
 * Se ejecuta todos los días a las 23:59
 */
class DailyAbsenceJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     * 
     * Encuentra turnos del día que no tienen ninguna marca
     * Crea registro en absences y actualiza estado del turno
     */
    public function handle(AbsenceService $absenceService): void
    {
        try {
            Log::info('Iniciando procesamiento de ausencias diarias');

            $result = $absenceService->processDailyAbsences();

            Log::info('Procesamiento de ausencias completado', $result);
        } catch (\Exception $e) {
            Log::error('Error en DailyAbsenceJob', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
