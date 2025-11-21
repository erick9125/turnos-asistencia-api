<?php

namespace App\Application\Marks;

use App\Infrastructure\Persistence\Repositories\MarkRepository;
use App\Infrastructure\Persistence\Repositories\ShiftRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Servicio de aplicación para asociar marcas con turnos
 * Determina inicio y fin de turnos basado en marcas
 */
class MarkAssociationService
{
    public function __construct(
        private ShiftRepository $shiftRepository,
        private MarkRepository $markRepository
    ) {
    }

    /**
     * Procesa todos los turnos del día y asocia marcas
     * 
     * Proceso:
     * 1. Obtiene turnos del día (planned / in_progress)
     * 2. Busca marcas del rango de horas
     * 3. Determina primera entrada = inicio turno
     * 4. Determina última salida = término turno
     * 5. Actualiza estado: completed, in_progress, inconsistent
     */
    public function associateMarksWithShifts(): array
    {
        $today = Carbon::today();
        $shifts = $this->shiftRepository->getTodayShifts(['planned', 'in_progress']);

        $processed = 0;
        $updated = 0;

        foreach ($shifts as $shift) {
            try {
                $this->processShiftMarks($shift);
                $processed++;
                $updated++;
            } catch (\Exception $e) {
                Log::error('Error procesando turno', [
                    'shift_id' => $shift->id,
                    'error' => $e->getMessage(),
                ]);
                $processed++;
            }
        }

        Log::info('Asociación de marcas completada', [
            'processed' => $processed,
            'updated' => $updated,
        ]);

        return [
            'processed' => $processed,
            'updated' => $updated,
        ];
    }

    /**
     * Procesa las marcas de un turno específico
     */
    private function processShiftMarks($shift): void
    {
        // Obtener marcas del rango del turno (con margen de 1 hora antes y después)
        $startRange = $shift->start_at->copy()->subHour();
        $endRange = $shift->end_at->copy()->addHour();

        $marks = $this->markRepository->getMarksForShift(
            $shift->worker_id,
            $startRange,
            $endRange
        );

        if ($marks->isEmpty()) {
            // No hay marcas, mantener estado planned
            return;
        }

        // Obtener primera entrada y última salida
        $firstIn = $this->markRepository->getFirstInMark(
            $shift->worker_id,
            $startRange,
            $endRange
        );

        $lastOut = $this->markRepository->getLastOutMark(
            $shift->worker_id,
            $startRange,
            $endRange
        );

        // Determinar estado del turno
        $now = Carbon::now();
        $status = 'planned';

        if ($firstIn && $lastOut) {
            // Tiene entrada y salida
            if ($lastOut->marked_at->isBefore($now)) {
                $status = 'completed';
            } else {
                $status = 'in_progress';
            }
        } elseif ($firstIn && !$lastOut) {
            // Tiene entrada pero no salida
            if ($shift->end_at->isBefore($now)) {
                $status = 'inconsistent'; // Falta salida
            } else {
                $status = 'in_progress';
            }
        } elseif (!$firstIn && $lastOut) {
            // Tiene salida pero no entrada (caso raro)
            $status = 'inconsistent';
        }

        // Actualizar estado del turno
        $this->shiftRepository->update($shift, ['status' => $status]);
    }
}

