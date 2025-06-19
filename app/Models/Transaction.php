<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'amount',
        'payment_method',
        'status',
        'payment_date',
        'note',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
