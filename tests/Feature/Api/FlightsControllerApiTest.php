<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Plane;
use App\Models\Flight;
use Illuminate\Foundation\Testing\WithFaker;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlightsControllerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_display_all_flights()
    {
        $plane = Plane::create([
            'name' => 'Avión 1',
            'max_seats' => 100,
        ]);

        $flight = Flight::create([
            'departure_date' => '2025-03-28 17:27:58',
            'arrival_date' => '2025-03-28 18:27:58',
            'origin' => 'Madrid',
            'destination' => 'Barcelona',
            'plane_id' => $plane->id,
            'available' => true,
        ]);

        $response = $this->getJson(route('allFlights'));

        $response->assertStatus(200);
    }

    public function test_can_create_flight()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $plane = Plane::create([
            'name' => 'Avión 1',
            'max_seats' => 100,
        ]);

        $flightData = [
            'departure_date' => now()->addHour(),
            'arrival_date' => now()->addHours(2),
            'origin' => 'Madrid',
            'destination' => 'Barcelona',
            'plane_id' => $plane->id,
            'available' => 1,
        ];

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->post(route('createFlight'), $flightData);

        $response->assertStatus(201);
    }
    public function test_cant_create_two_flights_with_same_id_and_same_dates()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $plane = Plane::create([
            'name' => 'Avión 1',
            'max_seats' => 100,
        ]);

        $flight1 = Flight::create([
            'departure_date' => now()->addHour(),
            'arrival_date' => now()->addHours(2),
            'origin' => 'Madrid',
            'destination' => 'Barcelona',
            'plane_id' => $plane->id,
            'available' => true,
        ]);

        $flightData = [
            'departure_date' => now()->addHour(),
            'arrival_date' => now()->addHours(2),
            'origin' => 'Madrid',
            'destination' => 'Barcelona',
            'plane_id' => $plane->id,
            'available' => true,
        ];

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->post(route('createFlight'), $flightData);

        $response->assertJson(["error" => 'Ya existe un vuelo con el mismo avión y las mismas fechas.'], 422);
    }

    public function test_cant_create_flight_inactive()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $plane = Plane::create([
            'name' => 'Avión 1',
            'max_seats' => 100,
        ]);

        $flightData = [
            'departure_date' => now()->addHour(),
            'arrival_date' => now()->addHours(2),
            'origin' => 'Madrid',
            'destination' => 'Barcelona',
            'plane_id' => $plane->id,
            'available' => 0,
        ];

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->post(route('createFlight'), $flightData);

        $response->assertJson(["error" => 'No puedes crear un vuelo que no este activo'], 422);
    }

    public function test_show_of_flights_works()
    {

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
            'available' => 1,
        ]);

        $response = $this->get(route('flightShow', $flight->id));

        $response->assertStatus(200);
    }

    public function test_cant_show_non_existent_flight()
    {
        $response = $this->get(route('flightShow', 1));

        $response->assertJson(["message" => 'Vuelo no encontrado'],404);
    }

    public function test_can_update_a_flight()
    {

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
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
            'available' => 1,
        ]);

        $updateData = [
            'departure_date' => now()->addHour(2),
            'arrival_date' => now()->addHours(3),
            'origin' => 'Malaga',
            'destination' => 'Japón',
            'plane_id' => $plane->id,
            'available' => 0,
        ];

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->put(route('flightUpdate', $flight->id), $updateData);

        $response->assertStatus(200);
    }

    public function test_cant_update_non_existent_flight()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $updateData = [
            'departure_date' => now()->addHour(2),
            'arrival_date' => now()->addHours(3),
            'origin' => 'Malaga',
            'destination' => 'Japón',
            'plane_id' => 1,
            'available' => 0,
        ];

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->put(route('flightUpdate', 1), $updateData);

        $response->assertJson(["message" => 'Vuelo no encontrado'],404);
    }

    public function test_can_delete_flight()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
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
            'available' => 1,
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->delete(route('flightDelete', $flight->id));

        $response->assertJson(["message" => 'Vuelo eliminado exitosamente'], 200);
    }

    public function test_cant_delete_non_existent_flight()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->delete(route('flightDelete', 1));

        $response->assertJson(["message" => 'Vuelo no encontrado'], 404);
    }
}
