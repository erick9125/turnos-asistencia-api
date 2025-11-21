<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * 
     * Ejecuta los seeders en el orden correcto:
     * 1. Usuarios (necesario para autenticación)
     * 2. Estructura de clientes (holdings, companies, branches, areas)
     * 3. Datos de asistencia (devices, workers, shifts, marks)
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ClientStructureSeeder::class,
            AttendanceSeeder::class,
        ]);
    }
}
