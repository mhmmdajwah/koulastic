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
        Schema::create('transaksi_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_id') // Relasi ke tabel pemesanan
                ->constrained('pemesanan') // Cari tabel pemesanan
                ->onDelete('cascade'); // Lalu hapus data ini jika data pemesanan ikut dihapus
            $table->foreignId('acara_id');
            $table->integer('total_pembayaran');
            $table->string('metode_pembayaran');
            $table->enum('status', ['Belum Lunas', 'Lunas']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_masuk');
    }
};
