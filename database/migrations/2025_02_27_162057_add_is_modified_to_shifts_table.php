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
        Schema::table('shifts', function (Blueprint $table) {
            Schema::table('shifts', function (Blueprint $table) {
                $table->boolean('is_modified')->default(false)->after('end_time');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            Schema::table('shifts', function (Blueprint $table) {
                $table->dropColumn('is_modified');
            });
        });
    }
};
