<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Models\Attendance\Device;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repositorio para gestión de dispositivos
 * Encapsula todas las consultas relacionadas con devices
 */
class DeviceRepository
{
    /**
     * Busca un dispositivo por ID
     */
    public function findById(int $id): ?Device
    {
        return Device::with(['area'])->find($id);
    }

    /**
     * Busca un dispositivo por device_key
     */
    public function findByDeviceKey(string $deviceKey): ?Device
    {
        return Device::with(['area'])->where('device_key', $deviceKey)->first();
    }

    /**
     * Busca dispositivos con filtros opcionales
     */
    public function searchByFilters(array $filters = []): Collection
    {
        $query = Device::with(['area']);

        if (isset($filters['area_id'])) {
            $query->where('area_id', $filters['area_id']);
        }

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('name', 'asc')->get();
    }

    /**
     * Verifica si un dispositivo está activo
     */
    public function isActive(int $deviceId): bool
    {
        $device = $this->findById($deviceId);
        return $device && $device->status === 'active';
    }

    /**
     * Obtiene solo dispositivos activos
     */
    public function getActiveDevices(): Collection
    {
        return Device::with(['area'])->active()->get();
    }

    /**
     * Actualiza un dispositivo
     */
    public function update(Device $device, array $data): bool
    {
        return $device->update($data);
    }
}

