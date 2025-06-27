<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    use HasFactory;

    protected $table = 'finance';

    protected $fillable = [
        'total_expense',
        'notes',
    ];

    protected static function boot()
    {
        parent::boot();

        // static::creating(function ($finance) {
        //     $finance->balance = $finance->total_income - $finance->total_expense;
        // });

        // static::updating(function ($finance) {
        //     $finance->balance = $finance->total_income - $finance->total_expense;
        // });
    }
}
