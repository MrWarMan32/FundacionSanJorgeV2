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
            $table->boolean('is_recurring')->default(true); // Indica si la cita es recurrente
            $table->foreignId('parent_shift_id')->nullable()->constrained('shifts')->onDelete('cascade'); // Relaciona con la cita anterior
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropColumn('is_recurring');
            $table->dropForeign(['parent_shift_id']);
            $table->dropColumn('parent_shift_id');
        });
    }
};
