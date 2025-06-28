<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomFacility;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $room1 = Room::create([
            'name' => 'Deluxe Room',
            'description' => 'Kamar nyaman dengan fasilitas terbaik',
            'price_per_night' => 200000,
            'max_guest' => 2,
            'room_size' => '30',
            'bed_type' => 'King Bed',
            'image' => 'deluxe.jpg',
        ]);

        $room2 = Room::create([
            'name' => 'Superior Room',
            'description' => 'Kamar untuk keluarga kecil',
            'price_per_night' => 300000,
            'max_guest' => 3,
            'room_size' => '35',
            'bed_type' => 'Queen Bed',
            'image' => 'superior.jpg',
        ]);
        $room1->facilities()->attach(RoomFacility::inRandomOrder()->limit(4)->pluck('id'));
        $room2->facilities()->attach(RoomFacility::inRandomOrder()->limit(4)->pluck('id'));
    }
}
