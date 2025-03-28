<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Plane;
use App\Models\Flight;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlightModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_ItCreatesAFlight()
    {
        $plane = Plane::create([
            'name' => 'Avión 1',
            'max_seats' => 100
        ]);

        $flight = Flight::create([
            'departure_date' => now(),
            'arrival_date' => now()->addHours(2),
            'origin' => 'New York',
            'destination' => 'Los Angeles',
            'plane_id' => $plane->id,
            'available' => true,
        ]);

        $this->assertDatabaseHas('flights', [
            'id' => $flight->id,
            'plane_id' => $plane->id,
            'origin' => 'New York',
            'destination' => 'Los Angeles',
        ]);
    }
}
