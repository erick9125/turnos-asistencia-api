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
     * Crea la tabla de devices (dispositivos de marcación)
     */
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
            $table->string('name');
            $table->string('device_key')->unique();
            $table->enum('type', ['clock', 'logical', 'external'])->default('clock');
            $table->enum('status', ['active', 'disabled'])->default('active');
            $table->timestamps();
            $table->softDeletes(); // Eliminación suave
            
            // Índices para búsquedas
            $table->index('area_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
