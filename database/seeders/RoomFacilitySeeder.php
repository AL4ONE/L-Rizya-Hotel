<?php

namespace Database\Seeders;

use App\Models\RoomFacility;
use Illuminate\Database\Seeder;

class RoomFacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            'Hair dryer',
            'Hot and cold shower',
            'Non-smoking room',
            'LCD TV',
            'Bathrobe and slippers',
            'In-room safety deposit box',
            'WiFi',
            'King Bed'
        ];

        foreach ($facilities as $facility) {
            RoomFacility::create(['name' => $facility]);
        }
    }
}
