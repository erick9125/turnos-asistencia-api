<?php

namespace App\Jobs\Attendance;

use App\Application\Marks\MarkAssociationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * Job para asociar marcas con turnos
 * Se ejecuta cada 5 minutos
 */
class AssociateMarksJob implements ShouldQueue
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
     * Procesa todos los turnos del día y asocia marcas
     */
    public function handle(MarkAssociationService $markAssociationService): void
    {
        try {
            Log::info('Iniciando asociación de marcas con turnos');

            $result = $markAssociationService->associateMarksWithShifts();

            Log::info('Asociación de marcas completada', $result);
        } catch (\Exception $e) {
            Log::error('Error en AssociateMarksJob', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
