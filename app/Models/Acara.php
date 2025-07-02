<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acara extends Model
{
    use HasFactory;

    protected $table = 'acara';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function pemesanan()
    {
        return $this->hasOne(Pemesanan::class);
    }

    public function transaksiMasuk()
    {
        return $this->hasMany(TransaksiMasuk::class);
    }

    public function transaksiKeluar()
    {
        return $this->hasMany(TransaksiKeluar::class);
    }
}
