<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Flight;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlightModelsTest extends TestCase
{
    use RefreshDatabase;

    public function ItCreatesAFlight()
    {
        $flight = Flight::create([
            'departure_date' => now(),
            'arrival_date' => now()->addHours(2),
            'origin' => 'New York',
            'destination' => 'Los Angeles',
            'plane_id' => 1,
            'available' => true,
        ]);

        $this->assertDatabaseHas('flights', [
            'id' => $flight->id,
            'origin' => 'New York',
            'destination' => 'Los Angeles',
        ]);
    }
}
