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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('phone_number', 20)->nullable()->after('client_name');
            $table->text('booking_detail')->nullable()->after('location');
            $table->integer('price')->nullable()->after('booking_detail');
            $table->integer('down_payment')->nullable()->after('price');
            $table->string('payment_method', 50)->nullable()->after('down_payment');
            $table->date('payment_date')->nullable()->after('payment_method');
            $table->text('notes')->nullable()->after('payment_date');
            $table->string('payment_status', 20)->nullable()->after('notes');
            $table->integer('payment_balance')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'phone_number',
                'booking_detail',
                'price',
                'down_payment',
                'payment_method',
                'payment_date',
                'notes',
                'payment_status',
                'payment_balance',
            ]);
        });
    }
};
