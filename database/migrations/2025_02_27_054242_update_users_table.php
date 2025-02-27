<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('representative_name')->nullable();
            $table->string('representative_last_name')->nullable();
            $table->string('representative_id_card')->nullable();
            $table->dropColumn('disability'); // Eliminar campo antiguo
            $table->json('disability_type')->nullable(); // Permitir múltiples valores
            $table->enum('disability_level', ['proceso', 'leve', 'moderado', 'grave', 'muy grave'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['representative__name', 'representative_last_name', 'representative_id_card', 'disability_type', 'disability_level']);
            $table->string('disability')->nullable();
        });
    }
};
