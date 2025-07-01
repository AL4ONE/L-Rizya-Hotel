<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomFacility extends Model
{
    protected $guarded = [];
    public function rooms()
    {
        return $this->belongsToMany(Room::class);
    }


}
