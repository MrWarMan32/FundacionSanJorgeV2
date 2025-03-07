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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_provincia');
            $table->unsignedBigInteger('id_canton');
            $table->unsignedBigInteger('id_parroquia');
            $table->string('street_1')->nullable();
            $table->string('street_2')->nullable();
            $table->string('numero')->nullable();
            $table->text('reference')->nullable();

            $table->foreign('id_provincia')->references('id')->on('provincia');
            $table->foreign('id_canton')->references('id')->on('canton');
            $table->foreign('id_parroquia')->references('id')->on('parroquia');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
