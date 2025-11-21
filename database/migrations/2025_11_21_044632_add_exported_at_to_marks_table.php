<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Agrega el campo exported_at para control de sincronización con sistema legado
     */
    public function up(): void
    {
        Schema::table('marks', function (Blueprint $table) {
            $table->dateTime('exported_at')->nullable()->after('truncated_minute');
            $table->index('exported_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marks', function (Blueprint $table) {
            $table->dropIndex(['exported_at']);
            $table->dropColumn('exported_at');
        });
    }
};
