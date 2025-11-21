<?php

namespace App\Application\Marks;

use App\Exceptions\DuplicateMarkException;
use App\Exceptions\InactiveDeviceException;
use App\Exceptions\InactiveWorkerException;
use App\Infrastructure\Persistence\Repositories\DeviceRepository;
use App\Infrastructure\Persistence\Repositories\MarkRepository;
use App\Infrastructure\Persistence\Repositories\WorkerRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Servicio de aplicación para ingesta de marcas
 * Contiene la lógica de negocio para crear marcas desde diferentes fuentes
 */
class MarkIngestionService
{
    public function __construct(
        private MarkRepository $markRepository,
        private WorkerRepository $workerRepository,
        private DeviceRepository $deviceRepository
    ) {
    }

    /**
     * Crea una marca desde fuente remota (API)
     * 
     * Reglas de validación:
     * - worker existe y está activo
     * - device existe y está activo
     * - timestamp válido
     * - No duplicados (mismo worker + direction + minuto)
     */
    public function createRemoteMark(array $data): array
    {
        // Validar trabajador
        $worker = $this->workerRepository->findById($data['worker_id']);
        if (!$worker) {
            throw new \Exception('El trabajador no existe.');
        }

        if ($worker->status !== 'active') {
            throw new InactiveWorkerException('No se puede crear una marca para un trabajador inactivo.');
        }

        // Validar dispositivo
        $device = $this->deviceRepository->findById($data['device_id']);
        if (!$device) {
            throw new \Exception('El dispositivo no existe.');
        }

        if ($device->status !== 'active') {
            throw new InactiveDeviceException('No se puede crear una marca con un dispositivo inactivo.');
        }

        // Validar timestamp
        $markedAt = Carbon::parse($data['marked_at']);
        if (!$markedAt->isValid()) {
            throw new \Exception('El timestamp de la marca no es válido.');
        }

        // Calcular truncated_minute (sin segundos)
        $truncatedMinute = $markedAt->copy()->startOfMinute();

        // Validar duplicado
        if ($this->markRepository->existsDuplicate(
            $data['worker_id'],
            $data['direction'],
            $truncatedMinute
        )) {
            throw new DuplicateMarkException();
        }

        // Crear la marca
        $mark = $this->markRepository->createMark([
            'worker_id' => $data['worker_id'],
            'device_id' => $data['device_id'],
            'direction' => $data['direction'],
            'source_type' => 'remote',
            'marked_at' => $markedAt,
            'truncated_minute' => $truncatedMinute,
        ]);

        Log::info('Marca remota creada', [
            'mark_id' => $mark->id,
            'worker_id' => $mark->worker_id,
            'device_id' => $mark->device_id,
        ]);

        return $mark->toArray();
    }

    /**
     * Crea múltiples marcas desde reloj (batch)
     */
    public function createBatchClockMarks(array $marksData): array
    {
        $createdMarks = [];
        $errors = [];

        foreach ($marksData as $index => $markData) {
            try {
                // Validar trabajador
                $worker = $this->workerRepository->findByRut($markData['worker_rut']);
                if (!$worker) {
                    throw new \Exception("Trabajador con RUT {$markData['worker_rut']} no encontrado.");
                }

                if ($worker->status !== 'active') {
                    throw new InactiveWorkerException("Trabajador con RUT {$markData['worker_rut']} está inactivo.");
                }

                // Validar dispositivo por device_key
                $device = $this->deviceRepository->findByDeviceKey($markData['device_key']);
                if (!$device) {
                    throw new \Exception("Dispositivo con key {$markData['device_key']} no encontrado.");
                }

                if ($device->status !== 'active') {
                    throw new InactiveDeviceException("Dispositivo con key {$markData['device_key']} está inactivo.");
                }

                // Validar timestamp
                $markedAt = Carbon::parse($markData['marked_at']);
                if (!$markedAt->isValid()) {
                    throw new \Exception('El timestamp de la marca no es válido.');
                }

                // Calcular truncated_minute
                $truncatedMinute = $markedAt->copy()->startOfMinute();

                // Validar duplicado
                if ($this->markRepository->existsDuplicate(
                    $worker->id,
                    $markData['direction'],
                    $truncatedMinute
                )) {
                    throw new DuplicateMarkException();
                }

                // Crear la marca
                $mark = $this->markRepository->createMark([
                    'worker_id' => $worker->id,
                    'device_id' => $device->id,
                    'direction' => $markData['direction'],
                    'source_type' => 'clock',
                    'marked_at' => $markedAt,
                    'truncated_minute' => $truncatedMinute,
                ]);

                $createdMarks[] = $mark->toArray();
            } catch (\Exception $e) {
                $errors[] = [
                    'index' => $index,
                    'error' => $e->getMessage(),
                ];
            }
        }

        Log::info('Marcas batch procesadas', [
            'created' => count($createdMarks),
            'errors' => count($errors),
        ]);

        return [
            'created' => $createdMarks,
            'errors' => $errors,
        ];
    }

    /**
     * Crea una marca desde fuente externa
     */
    public function createExternalMark(array $data): array
    {
        // Similar a createRemoteMark pero con source_type = 'external'
        $data['source_type'] = 'external';

        // Validar trabajador
        $worker = $this->workerRepository->findById($data['worker_id']);
        if (!$worker) {
            throw new \Exception('El trabajador no existe.');
        }

        if ($worker->status !== 'active') {
            throw new InactiveWorkerException('No se puede crear una marca para un trabajador inactivo.');
        }

        // Validar dispositivo
        $device = $this->deviceRepository->findById($data['device_id']);
        if (!$device) {
            throw new \Exception('El dispositivo no existe.');
        }

        if ($device->status !== 'active') {
            throw new InactiveDeviceException('No se puede crear una marca con un dispositivo inactivo.');
        }

        // Validar timestamp
        $markedAt = Carbon::parse($data['marked_at']);
        if (!$markedAt->isValid()) {
            throw new \Exception('El timestamp de la marca no es válido.');
        }

        // Calcular truncated_minute
        $truncatedMinute = $markedAt->copy()->startOfMinute();

        // Validar duplicado
        if ($this->markRepository->existsDuplicate(
            $data['worker_id'],
            $data['direction'],
            $truncatedMinute
        )) {
            throw new DuplicateMarkException();
        }

        // Crear la marca
        $mark = $this->markRepository->createMark([
            'worker_id' => $data['worker_id'],
            'device_id' => $data['device_id'],
            'direction' => $data['direction'],
            'source_type' => 'external',
            'marked_at' => $markedAt,
            'truncated_minute' => $truncatedMinute,
        ]);

        Log::info('Marca externa creada', [
            'mark_id' => $mark->id,
            'worker_id' => $mark->worker_id,
            'device_id' => $mark->device_id,
        ]);

        return $mark->toArray();
    }
}

