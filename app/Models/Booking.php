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
        'end_hour',
        'start_time',
        'end_time',
        'total_price',
        'status',
        'notes',
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

    public function hourLabel(): string
    {
        $s = str_pad((string) $this->start_hour, 2, '0', STR_PAD_LEFT) . ':00';
        $e = str_pad((string) $this->end_hour, 2, '0', STR_PAD_LEFT) . ':00';
        return "{$s} - {$e}";
    }

    public function durationHours(): int
    {
        return max(1, (int) $this->end_hour - (int) $this->start_hour);
    }
}
