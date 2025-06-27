<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKeluar extends Model
{
    use HasFactory;

    protected $table = 'transaksi_keluar';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function acara()
    {
        return $this->belongsTo(Acara::class);
    }
}
