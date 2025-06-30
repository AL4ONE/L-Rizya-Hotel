<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
    ];

    public function payment()
    {
        return $this->hasOne(Payment::class, 'booking_code', 'booking_code');
    }

    public function room()
{
    return $this->belongsTo(Room::class);
}

}
