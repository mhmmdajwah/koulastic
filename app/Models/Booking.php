<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'event_name',
        'event_date',
        'location',
        'status',
        'phone_number',
        'booking_detail',
        'price',
        'down_payment',
        'payment_method',
        'payment_date',
        'notes',
        'payment_status',
        'payment_balance',
    ];
}
