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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con la tabla de usuarios
            $table->dateTime('appointment_time'); // Hora y fecha de la cita
            $table->enum('status', ['pendiente','completada', 'cancelada'])->default('pendiente'); // Estado de la cita
            $table->text('notes')->nullable(); // Notas sobre la cita
            $table->timestamps(); // Registra cuándo se creó y actualizó la cita
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
