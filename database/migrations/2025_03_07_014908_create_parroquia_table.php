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
        Schema::create('parroquia', function (Blueprint $table) {
            $table->id();
            $table->text('parroquia');
            $table->unsignedBigInteger('id_canton');
            $table->foreign('id_canton')->references('id')->on('canton')->onDelete('restrict')->onUpdate('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parroquia');
    }
};
