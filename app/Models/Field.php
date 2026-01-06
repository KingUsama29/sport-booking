<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Field extends Model
{
    protected $fillable = [
        'name', 'sport_type', 'price_per_hour', 'location', 'description'
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
