<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Plane;
use App\Models\Flight;
use App\Models\Booking;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingModelsTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_creates_a_booking_with_associations()
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

        $flight = Flight::create([
            'departure_date' => now()->addHours(5),
            'arrival_date' => now()->addHours(8), 
            'origin' => 'Madrid',
            'destination' => 'Barcelona',
            'plane_id' => $plane->id,
            'available' => true,
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'flight_id' => $flight->id,
            'plane_id' => $flight->plane_id,
            'seat_number' => 'A1',
            'status' => 'Activo'
        ]);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'flight_id' => $flight->id,
            'plane_id' => $flight->plane_id,
            'seat_number' => 'A1',
            'status' => 'Activo'
        ]);

        $this->assertEquals($booking->flight->id, $flight->id);

        $this->assertEquals($booking->user->id, $user->id);
    }

    public function test_it_fetches_related_flight_and_user()
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

            $flight = Flight::create([
            'departure_date' => now()->addHours(5),
            'arrival_date' => now()->addHours(8), 
            'origin' => 'Madrid',
            'destination' => 'Barcelona',
            'plane_id' => $plane->id,
            'available' => true,
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'flight_id' => $flight->id,
            'plane_id' => $flight->plane_id,
            'seat_number' => 'B2',
            'status' => 'Activo'
        ]);
        
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'flight_id' => $flight->id,
            'plane_id' => $flight->plane_id,
            'seat_number' => 'B2',
            'status' => 'Activo'
            ]);
    }
}
