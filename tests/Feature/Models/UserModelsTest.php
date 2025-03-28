<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Plane;
use App\Models\Flight;
use App\Models\Booking;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelsTest extends TestCase
{
    public function test_realtion_with_bookings()
    {
        $plane = Plane::create([
            'name' => 'Avión 1',
            'max_seats' => 100
        ]);

        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password')
        ]);

        $flight1 = Flight::create([
            'departure_date' => now()->addHours(5),
            'arrival_date' => now()->addHours(8), 
            'origin' => 'Madrid',
            'destination' => 'Barcelona',
            'plane_id' => $plane->id,
            'available' => true,
        ]);

        $flight2 = Flight::create([
            'departure_date' => now()->addHours(3),
            'arrival_date' => now()->addHours(4), 
            'origin' => 'Madrid',
            'destination' => 'Barcelona',
            'plane_id' => $plane->id,
            'available' => true,
        ]);

        $booking1 = Booking::create([
            'user_id' => $user->id,
            'flight_id' => $flight1->id,
            'seat_number' => 'B1',
            'status' => 'Activo'
        ]);

        $booking2 = Booking::create([
            'user_id' => $user->id,
            'flight_id' => $flight2->id,
            'seat_number' => 'B2',
            'status' => 'Activo'
        ]);

        $this->assertCount(2, $user->bookings);
    }
}
