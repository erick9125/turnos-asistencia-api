<?php

namespace App\Application\Attendance;

use App\Infrastructure\Persistence\Repositories\ShiftRepository;
use App\Models\Attendance\Absence;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Servicio de aplicación para gestión de ausencias
 * Contiene la lógica de negocio relacionada con absences
 */
class AbsenceService
{
    public function __construct(
        private ShiftRepository $shiftRepository
    ) {
    }

    /**
     * Procesa ausencias del día
     * 
     * Encuentra turnos del día que no tienen ninguna marca
     * Crea registro en absences y actualiza estado del turno a 'absent'
     */
    public function processDailyAbsences(): array
    {
        $today = Carbon::today();
        $shiftsWithoutMarks = $this->shiftRepository->getShiftsWithoutMarksForDate($today);

        $created = 0;

        foreach ($shiftsWithoutMarks as $shift) {
            try {
                // Crear ausencia
                Absence::create([
                    'shift_id' => $shift->id,
                    'worker_id' => $shift->worker_id,
                    'date' => $today,
                    'reason' => 'Sin marcas de asistencia',
                ]);

                // Actualizar estado del turno
                $this->shiftRepository->update($shift, ['status' => 'absent']);

                $created++;
            } catch (\Exception $e) {
                Log::error('Error creando ausencia', [
                    'shift_id' => $shift->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Procesamiento de ausencias completado', [
            'date' => $today->toDateString(),
            'created' => $created,
        ]);

        return [
            'date' => $today->toDateString(),
            'created' => $created,
        ];
    }
}

