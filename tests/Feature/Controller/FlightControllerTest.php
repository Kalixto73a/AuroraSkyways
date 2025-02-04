<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Flight;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlightControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testItDisplaysTheFlightViewWithAllFlights(): void
    {
        $this->seed(DatabaseSeeder::class);
        $response = $this->get(route('flights'));
        $response->assertStatus(200);
        $response->assertViewIs('flightsView');
        $response->assertViewHas('flights', Flight::all());
    }
}
