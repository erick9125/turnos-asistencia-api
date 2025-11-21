<?php

namespace Database\Seeders;

use App\Models\Attendance\Worker;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder para crear usuarios iniciales del sistema
 * 
 * Crea tres usuarios de ejemplo según los requerimientos:
 * - Administrador (admin@test.com)
 * - Jefatura/Manager (manager@test.com)
 * - Trabajador con user asociado (worker@test.com) asociado a un Worker real
 * 
 * Estos usuarios permiten probar el sistema de roles y autenticación
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Administrador del Sistema',
                'email' => 'admin@test.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // Jefatura/Manager
        $manager = User::firstOrCreate(
            ['email' => 'manager@test.com'],
            [
                'name' => 'Jefatura de Ejemplo',
                'email' => 'manager@test.com',
                'password' => Hash::make('manager123'),
                'role' => 'manager',
                'status' => 'active',
            ]
        );

        // Trabajador con user asociado
        // Buscar un worker existente o crear uno si no existe
        $worker = Worker::where('rut', '11111111-1')->first();
        
        if (!$worker) {
            // Si no hay workers, crear uno básico (requiere área)
            $areas = \App\Models\Client\Area::all();
            if ($areas->isEmpty()) {
                $this->command->warn('No hay áreas disponibles. El worker se creará sin área.');
                $worker = Worker::create([
                    'name' => 'Trabajador de Prueba',
                    'rut' => '11111111-1',
                    'status' => 'active',
                ]);
            } else {
                $worker = Worker::create([
                    'area_id' => $areas->first()->id,
                    'name' => 'Trabajador de Prueba',
                    'rut' => '11111111-1',
                    'status' => 'active',
                ]);
            }
        }

        // Crear usuario worker y asociarlo al worker
        $workerUser = User::firstOrCreate(
            ['email' => 'worker@test.com'],
            [
                'name' => 'Trabajador de Ejemplo',
                'email' => 'worker@test.com',
                'password' => Hash::make('worker123'),
                'role' => 'worker',
                'status' => 'active',
            ]
        );

        // Asociar el usuario al worker
        if (!$worker->user_id) {
            $worker->update(['user_id' => $workerUser->id]);
        }

        $this->command->info('Usuarios creados exitosamente:');
        $this->command->info('- admin@test.com / admin123 (rol: admin)');
        $this->command->info('- manager@test.com / manager123 (rol: manager)');
        $this->command->info('- worker@test.com / worker123 (rol: worker, asociado a worker ID: ' . $worker->id . ')');
    }
}
