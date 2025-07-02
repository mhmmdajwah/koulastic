<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function acara()
    {
        return $this->hasOne(Acara::class, 'id', 'acara_id');
    }

    public function transaksiMasuk()
    {
        return $this->hasMany(TransaksiMasuk::class);
    }

    public function getSisaPembayaran()
    {
        // Menghitung total yang sudah dibayar oleh pemesan
        $totalDibayar = $this->transaksiMasuk()->sum('total_pembayaran');
        // Mengambil harga acara
        $hargaAcara = $this->acara->harga;

        return $hargaAcara - $totalDibayar;
    }

    public function getTotalSudahBayar()
    {
        return $this->transaksiMasuk->sum('total_pembayaran');
    }
}
