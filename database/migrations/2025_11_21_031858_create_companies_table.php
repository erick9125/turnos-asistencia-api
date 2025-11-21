<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     * 
     * Crea la tabla de companies (empresas pertenecientes a un holding)
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('holding_id')->constrained('holdings')->onDelete('cascade');
            $table->string('name');
            $table->string('rut')->unique();
            $table->timestamps();
            $table->softDeletes(); // Eliminación suave
            
            // Índice para búsquedas por holding
            $table->index('holding_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
