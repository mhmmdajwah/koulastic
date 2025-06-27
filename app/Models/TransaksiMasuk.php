<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiMasuk extends Model
{
    use HasFactory;

    protected $table = 'transaksi_masuk';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function pemesanan() // Mendapatkan relasi pemesanan
    {
        return $this->belongsTo(Pemesanan::class);
    }
}
