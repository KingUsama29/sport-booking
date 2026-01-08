<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'field_id',
        'booking_date',
        'start_hour',
        'total',
        'status',
        'payment_status',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'snap_token',
        'paid_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // helper buat text jam
    public function hourLabel(): string
    {
        $h = (int) $this->start_hour;
        return str_pad($h, 2, '0', STR_PAD_LEFT).":00 - ".str_pad($h + 1, 2, '0', STR_PAD_LEFT).":00";
    }
}
