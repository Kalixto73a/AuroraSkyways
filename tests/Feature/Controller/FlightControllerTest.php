<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use App\Models\User;
use App\Models\Plane;
use App\Models\Flight;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Booking;

class FlightControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_index_flights_view()
    {
        $user = User::create([
            'name' => 'Juan',
            'email' => 'juan@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        $plane = Plane::create([
            'name' => 'Avión 1',
            'max_seats' => 100,
        ]);

        $flight = Flight::create([
            'departure_date' => now()->addHour(),
            'arrival_date' => now()->addHours(2),
            'origin' => 'Madrid',
            'destination' => 'Barcelona',
            'plane_id' => $plane->id,
            'available' => true,
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'flight_id' => $flight->id,
            'plane_id' => $plane->id,
            'seat_number' => 'A1',
            'status' => 'Activo',
        ]);

        $response = $this->get(route('flights')); 

        $response->assertStatus(200)
                 ->assertViewIs('flightsView')
                 ->assertViewHas('flights')
                 ->assertSee($flight->name);
    }
}
