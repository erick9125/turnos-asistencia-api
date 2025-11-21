<?php

namespace App\Application\Devices;

use App\Infrastructure\Persistence\Repositories\DeviceRepository;
use Illuminate\Support\Facades\Log;

/**
 * Servicio de aplicación para gestión de dispositivos
 * Contiene la lógica de negocio relacionada con devices
 */
class DeviceService
{
    public function __construct(
        private DeviceRepository $deviceRepository
    ) {
    }

    /**
     * Obtiene todos los dispositivos con filtros opcionales
     */
    public function getAllDevices(array $filters = []): array
    {
        return $this->deviceRepository->searchByFilters($filters)->toArray();
    }

    /**
     * Obtiene un dispositivo por ID
     */
    public function getDeviceById(int $id): ?array
    {
        $device = $this->deviceRepository->findById($id);
        return $device ? $device->toArray() : null;
    }

    /**
     * Crea un nuevo dispositivo
     */
    public function createDevice(array $data): array
    {
        // Verificar que device_key sea único
        $existing = $this->deviceRepository->findByDeviceKey($data['device_key']);
        if ($existing) {
            throw new \Exception('Ya existe un dispositivo con este device_key.');
        }

        $device = \App\Models\Attendance\Device::create([
            'area_id' => $data['area_id'],
            'name' => $data['name'],
            'device_key' => $data['device_key'],
            'type' => $data['type'] ?? 'clock',
            'status' => $data['status'] ?? 'active',
        ]);

        Log::info('Dispositivo creado', ['device_id' => $device->id]);

        return $device->toArray();
    }

    /**
     * Desactiva un dispositivo
     */
    public function disableDevice(int $id): array
    {
        $device = $this->deviceRepository->findById($id);
        if (!$device) {
            throw new \Exception('El dispositivo no existe.');
        }

        $this->deviceRepository->update($device, ['status' => 'disabled']);

        Log::info('Dispositivo desactivado', ['device_id' => $id]);

        return $device->fresh()->toArray();
    }
}

