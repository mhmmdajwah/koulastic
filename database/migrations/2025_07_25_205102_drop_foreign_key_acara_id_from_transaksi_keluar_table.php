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
        Schema::table('transaksi_keluar', function (Blueprint $table) {
            $table->dropForeign(['acara_id']);

            $table->dropColumn('acara_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_keluar', function (Blueprint $table) {
            $table->foreignId('acara_id')
                ->constrained('acara')
                ->onDelete('cascade');
        });
    }
};
