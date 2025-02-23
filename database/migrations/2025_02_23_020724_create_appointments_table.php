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
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade'); // Relación con la tabla de usuarios
            $table->foreignId('therapy_id')->constrained('therapies')->onDelete('cascade'); // Relación con la tabla de terapias
            $table->string('day'); // Día de la cita
            $table->time('start_time');  // Hora de inicio de la cita
            $table->time('end_time'); // Hora de fin de la cita
            $table->boolean('available')->default(true);  // Indica si el horario está disponible
            $table->timestamps();
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
