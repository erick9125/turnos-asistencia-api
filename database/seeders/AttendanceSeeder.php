<?php

namespace Database\Seeders;

use App\Models\Attendance\Device;
use App\Models\Attendance\Mark;
use App\Models\Attendance\Shift;
use App\Models\Attendance\Worker;
use App\Models\Client\Area;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Seeder para crear datos de asistencia
 * 
 * Crea:
 * - Dispositivos de ejemplo (clock, logical, external)
 * - Trabajadores de ejemplo
 * - Turnos de ejemplo (opcional)
 * - Marcas de ejemplo (opcional)
 * 
 * Estos datos permiten probar el flujo completo del sistema
 */
class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener áreas existentes
        $areas = Area::all();
        if ($areas->isEmpty()) {
            $this->command->warn('No hay áreas disponibles. Ejecuta ClientStructureSeeder primero.');
            return;
        }

        $area1 = $areas->first();
        $area2 = $areas->skip(1)->first() ?? $area1;

        // Crear dispositivos de ejemplo
        $deviceClock = Device::firstOrCreate(
            ['device_key' => 'CLOCK-001'],
            [
                'area_id' => $area1->id,
                'name' => 'Reloj Biométrico Principal',
                'device_key' => 'CLOCK-001',
                'type' => 'clock',
                'status' => 'active',
            ]
        );

        $deviceLogical = Device::firstOrCreate(
            ['device_key' => 'REMOTE_APP'],
            [
                'area_id' => $area1->id,
                'name' => 'Aplicación Remota',
                'device_key' => 'REMOTE_APP',
                'type' => 'logical',
                'status' => 'active',
            ]
        );

        $deviceExternal = Device::firstOrCreate(
            ['device_key' => 'EXTERNAL-001'],
            [
                'area_id' => $area2->id,
                'name' => 'Sistema Externo de Integración',
                'device_key' => 'EXTERNAL-001',
                'type' => 'external',
                'status' => 'active',
            ]
        );

        // Crear trabajadores de ejemplo
        $worker1 = Worker::firstOrCreate(
            ['rut' => '11111111-1'],
            [
                'area_id' => $area1->id,
                'name' => 'Juan Pérez',
                'rut' => '11111111-1',
                'status' => 'active',
            ]
        );

        $worker2 = Worker::firstOrCreate(
            ['rut' => '22222222-2'],
            [
                'area_id' => $area1->id,
                'name' => 'María González',
                'rut' => '22222222-2',
                'status' => 'active',
            ]
        );

        $worker3 = Worker::firstOrCreate(
            ['rut' => '33333333-3'],
            [
                'area_id' => $area2->id,
                'name' => 'Carlos Rodríguez',
                'rut' => '33333333-3',
                'status' => 'active',
            ]
        );

        // Crear turnos de ejemplo para hoy
        $today = Carbon::today();
        $shift1 = Shift::firstOrCreate(
            [
                'worker_id' => $worker1->id,
                'start_at' => $today->copy()->setTime(8, 0),
            ],
            [
                'worker_id' => $worker1->id,
                'area_id' => $area1->id,
                'start_at' => $today->copy()->setTime(8, 0),
                'end_at' => $today->copy()->setTime(17, 0),
                'status' => 'planned',
            ]
        );

        $shift2 = Shift::firstOrCreate(
            [
                'worker_id' => $worker2->id,
                'start_at' => $today->copy()->setTime(9, 0),
            ],
            [
                'worker_id' => $worker2->id,
                'area_id' => $area1->id,
                'start_at' => $today->copy()->setTime(9, 0),
                'end_at' => $today->copy()->setTime(18, 0),
                'status' => 'planned',
            ]
        );

        // Crear algunas marcas de ejemplo (opcional)
        if ($this->command->confirm('¿Deseas crear marcas de ejemplo?', false)) {
            $now = Carbon::now();
            
            Mark::firstOrCreate(
                [
                    'worker_id' => $worker1->id,
                    'direction' => 'in',
                    'truncated_minute' => $now->copy()->startOfMinute(),
                ],
                [
                    'worker_id' => $worker1->id,
                    'device_id' => $deviceClock->id,
                    'direction' => 'in',
                    'source_type' => 'clock',
                    'marked_at' => $now->copy()->subMinutes(30),
                    'truncated_minute' => $now->copy()->subMinutes(30)->startOfMinute(),
                ]
            );
        }

        $this->command->info('Datos de asistencia creados exitosamente:');
        $this->command->info("- 3 Dispositivos (clock, logical, external)");
        $this->command->info("- 3 Trabajadores");
        $this->command->info("- 2 Turnos de ejemplo");
    }
}

