<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Actualiza el enum de role para incluir 'admin'
     * Nota: En MySQL/MariaDB, cambiar un enum requiere recrear la columna
     */
    public function up(): void
    {
        // Para MySQL/MariaDB, necesitamos modificar la columna directamente
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('worker', 'manager', 'admin') DEFAULT 'worker'");
        } else {
            // Para PostgreSQL u otros, usar Schema
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['worker', 'manager', 'admin'])->default('worker')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a enum anterior sin admin
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('worker', 'manager') DEFAULT 'worker'");
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['worker', 'manager'])->default('worker')->change();
            });
        }
    }
};
