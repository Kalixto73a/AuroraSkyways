<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Plane;
use App\Models\Flight;
use App\Models\Booking;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateFlightStatusTest extends TestCase
{
    use RefreshDatabase; 
    
    public function test_it_updates_flight_status_based_on_conditions()
    {

        $plane1 = Plane::create([
            'name' => 'Avión 1',
            'max_seats' => 0
        ]);

        $plane2 = Plane::create([
            'name' => 'Avión 1',
            'max_seats' => 10
        ]);

        $user1 = User::create([
            'name' => 'Juan',
            'email' => 'juan@example.com',
            'password' => bcrypt('password')
        ]);

        $user2 = User::create([
            'name' => 'Juan2',
            'email' => 'juan2@example.com',
            'password' => bcrypt('password')
        ]);

        $flight1 = Flight::create([
            'remaining_capacity' => 0,
            'departure_date' => now()->addHour(),
            'arrival_date' => now()->addHours(2), 
            'origin' => 'Madrid', 
            'destination' => 'Barcelona',     
            'plane_id' => $plane1->id,
            'available' => true,
        ]);

        $flight2 = Flight::create([
            'remaining_capacity' => 10,
            'departure_date' => now()->addHour(3),
            'arrival_date' => now()->addHours(4), 
            'origin' => 'Madrid', 
            'destination' => 'Barcelona',     
            'plane_id' => $plane2->id,
            'available' => true,
        ]);
        
        $booking1 = Booking::create([
            'user_id' => $user1->id,
            'plane_id' => $plane1->id,
            'flight_id' => $flight1->id,
            'seat_number' => 'A1',
            'status' => 'Activo'
        ]);

        $booking2 = Booking::create([
            'user_id' => $user2->id,
            'plane_id' => $plane2->id,
            'flight_id' => $flight2->id,
            'seat_number' => 'A2',
            'status' => 'Activo'
        ]);

        $flight1->updateStatus();
        $flight2->updateStatus();
        $this->assertEquals(false, $flight1->available);
        $this->assertEquals(true, $flight2->available);

    }
}
