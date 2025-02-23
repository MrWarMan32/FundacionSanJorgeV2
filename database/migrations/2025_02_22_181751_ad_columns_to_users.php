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
            $table->string('canton')->nullable();
            $table->string('parish')->nullable();
            $table->string('site')->nullable();
            $table->string('street_1')->nullable();
            $table->string('street_2')->nullable();
            $table->string('reference')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('canton');
            $table->dropColumn('parish');
            $table->dropColumn('site');
            $table->dropColumn('street_1');
            $table->dropColumn('street_2');
            $table->dropColumn('reference');
        });
    }
};
