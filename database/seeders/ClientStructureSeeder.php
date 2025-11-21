<?php

namespace Database\Seeders;

use App\Models\Client\Area;
use App\Models\Client\Branch;
use App\Models\Client\Company;
use App\Models\Client\Holding;
use Illuminate\Database\Seeder;

/**
 * Seeder para crear la estructura de clientes
 * 
 * Crea una estructura jerárquica de ejemplo:
 * - Holding: Grupo empresarial principal
 * - Company: Empresas dentro del holding
 * - Branch: Sucursales de cada empresa
 * - Area: Áreas de trabajo dentro de cada sucursal
 * 
 * Esta estructura permite probar el sistema completo de gestión
 */
class ClientStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear Holding principal
        $holding = Holding::firstOrCreate(
            ['name' => 'Grupo Empresarial Demo'],
            ['name' => 'Grupo Empresarial Demo']
        );

        // Crear Companies
        $company1 = Company::firstOrCreate(
            ['rut' => '12345678-9'],
            [
                'holding_id' => $holding->id,
                'name' => 'Empresa Principal S.A.',
                'rut' => '12345678-9',
            ]
        );

        $company2 = Company::firstOrCreate(
            ['rut' => '98765432-1'],
            [
                'holding_id' => $holding->id,
                'name' => 'Empresa Secundaria Ltda.',
                'rut' => '98765432-1',
            ]
        );

        // Crear Branches para Company 1
        $branch1 = Branch::firstOrCreate(
            ['company_id' => $company1->id, 'code' => 'SUC-001'],
            [
                'company_id' => $company1->id,
                'name' => 'Sucursal Centro',
                'code' => 'SUC-001',
            ]
        );

        $branch2 = Branch::firstOrCreate(
            ['company_id' => $company1->id, 'code' => 'SUC-002'],
            [
                'company_id' => $company1->id,
                'name' => 'Sucursal Norte',
                'code' => 'SUC-002',
            ]
        );

        // Crear Branches para Company 2
        $branch3 = Branch::firstOrCreate(
            ['company_id' => $company2->id, 'code' => 'SUC-003'],
            [
                'company_id' => $company2->id,
                'name' => 'Sucursal Sur',
                'code' => 'SUC-003',
            ]
        );

        // Crear Areas para Branch 1
        $area1 = Area::firstOrCreate(
            ['branch_id' => $branch1->id, 'code' => 'AREA-001'],
            [
                'branch_id' => $branch1->id,
                'name' => 'Área de Producción',
                'code' => 'AREA-001',
            ]
        );

        $area2 = Area::firstOrCreate(
            ['branch_id' => $branch1->id, 'code' => 'AREA-002'],
            [
                'branch_id' => $branch1->id,
                'name' => 'Área de Logística',
                'code' => 'AREA-002',
            ]
        );

        // Crear Areas para Branch 2
        $area3 = Area::firstOrCreate(
            ['branch_id' => $branch2->id, 'code' => 'AREA-003'],
            [
                'branch_id' => $branch2->id,
                'name' => 'Área de Ventas',
                'code' => 'AREA-003',
            ]
        );

        // Crear Areas para Branch 3
        $area4 = Area::firstOrCreate(
            ['branch_id' => $branch3->id, 'code' => 'AREA-004'],
            [
                'branch_id' => $branch3->id,
                'name' => 'Área Administrativa',
                'code' => 'AREA-004',
            ]
        );

        $this->command->info('Estructura de clientes creada exitosamente:');
        $this->command->info("- 1 Holding, 2 Companies, 3 Branches, 4 Areas");
    }
}

