<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookings = [
            [
                'user_id' => 1,
                'flight_id' => 1,
                'seat_number' => 12,
                'status' => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'flight_id' => 1,
                'seat_number' => 15,
                'status' => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'flight_id' => 1,
                'seat_number' => 5,
                'status' => 'Inactivo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'flight_id' => 1,
                'seat_number' => 20,
                'status' => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        foreach ($bookings as $booking) {
            Booking::create($booking);
        }
    }
}
