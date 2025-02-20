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
            $table->string('last_name', 100)->nullable();
            $table->bigInteger('id_card')->unique()->nullable();
            $table->enum('gender', ['Masculino', 'Femenino', 'Otro']);
            $table->date('birth_date')->nullable();
            $table->integer('age')->nullable();
            $table->string('ethnicity', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->enum('user_type', ['admin', 'doctor', 'paciente', 'usuario'])->default('usuario');
            $table->enum('status', ['aspirante', 'paciente'])->default('aspirante');
            $table->text('disability')->nullable();
            $table->boolean('id_card_status')->default(0);
            $table->integer('disability_grade')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('medical_history')->nullable();
            $table->foreignId('address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->foreignId('therapy_id')->nullable()->constrained('therapies')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_name');
            $table->dropColumn('id_card');
            $table->dropColumn('gender');
            $table->dropColumn('birth_date');
            $table->dropColumn('age');
            $table->dropColumn('ethnicity');
            $table->dropColumn('phone');
            $table->dropColumn('user_type');
            $table->dropColumn('status');
            $table->dropColumn('disability');
            $table->dropColumn('id_card_status');
            $table->dropColumn('disability_grade');
            $table->dropColumn('diagnosis');
            $table->dropColumn('medical_history');
            $table->dropForeign(['address_id']);
            $table->dropForeign(['therapy_id']);
            $table->dropColumn('address_id');
            $table->dropColumn('therapy_id');
        });
    }
};
