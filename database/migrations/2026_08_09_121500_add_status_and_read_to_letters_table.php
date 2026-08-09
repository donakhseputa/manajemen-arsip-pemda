<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->string('status')->default('belum_diproses');
            $table->boolean('is_read')->default(false);
        });

        DB::table('letters')->update([
            'status' => 'belum_diproses',
            'is_read' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->dropColumn(['status', 'is_read']);
        });
    }
};
