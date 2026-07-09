<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            ['name' => 'Chair 1', 'type' => 'treatment', 'description' => 'General dentistry chair'],
            ['name' => 'Chair 2', 'type' => 'treatment', 'description' => 'General dentistry chair'],
            ['name' => 'Chair 3', 'type' => 'surgery', 'description' => 'Minor oral surgery chair'],
            ['name' => 'Consultation Room', 'type' => 'consultation', 'description' => 'Private consultation room'],
        ];

        foreach ($rooms as $room) {
            Room::updateOrCreate(['name' => $room['name']], $room);
        }
    }
}
