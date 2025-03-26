<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Plane;
use App\Models\Flight;
use App\Models\Booking;
use Illuminate\Foundation\Testing\WithFaker;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlanesControllerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_plane_successfully()
    {

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        
        $token = JWTAuth::fromUser($user);

        $planeData = [
            'name' => 'Boeing 747',
            'max_seats' => 200,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->post(route('createPlanes'), $planeData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Avión creado exitosamente',
                'plane' => [
                    'name' => 'Boeing 747',
                    'max_seats' => 200,
                ],
            ]);

        $this->assertDatabaseHas('planes', [
            'name' => 'Boeing 747',
            'max_seats' => 200,
        ]);
    }
    public function test_show_returns_plane_with_flights_and_bookings()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $token = JWTAuth::fromUser($user);

        $plane = Plane::create([
            'name' => 'Boeing 747',
            'max_seats' => 200,
        ]);

        $flight = Flight::create([
            'departure_date' => now()->addDay(),
            'arrival_date' => now()->addDays(2),
            'origin' => 'Madrid',
            'destination' => 'Barcelona',
            'plane_id' => $plane->id,
            'available' => true,
        ]);

        $passenger = User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $booking = Booking::create([
            'user_id' => $passenger->id,
            'plane_id' => $plane->id,
            'flight_id' => $flight->id,
            'seat_number' => 'A1',
            'status' => 'Activo',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->get(route('planeShow', $plane->id));

        $response->assertJsonFragment([
            'id' => $plane->id,
            'name' => 'Boeing 747',
            'max_seats' => (string) $plane->max_seats,
        ]);
        
        $response->assertJsonPath('flights.0.bookings.0.seat_number', 'A1');
        $response->assertJsonPath('flights.0.bookings.0.user.name', 'Juan Pérez');
        $response->assertJsonPath('flights.0.bookings.0.user.email', 'juan@example.com');
        
    }

    public function test_show_returns_404_if_plane_not_found()
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
        ])->get(route('planeShow', 999));

        $response->assertStatus(404)
            ->assertJson(['message' => 'Avión no encontrado']);
    }

    public function test_update_plane_successfully()
    {
        
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $token = JWTAuth::fromUser($user);

        $plane = Plane::create([
            'name' => 'Boeing 747',
            'max_seats' => 200,
        ]);

        $updateData = [
            'name' => 'Airbus A380',
            'max_seats' => 300,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->put(route('planeUpdate', $plane->id), $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Avión actualizado exitosamente',
                'plane' => [
                    'id' => $plane->id,
                    'name' => 'Airbus A380',
                    'max_seats' => 300,
                ],
            ]);

        $this->assertDatabaseHas('planes', [
            'id' => $plane->id,
            'name' => 'Airbus A380',
            'max_seats' => 300,
        ]);
    }

    public function test_update_plane_not_found()
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
        ])->put(route('planeUpdate', 9999), [
            'name' => 'Avión Fantasma',
            'max_seats' => 500,
        ]);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Avión no encontrado']);
    }

    public function test_destroy_plane_successfully()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $token = JWTAuth::fromUser($user);

        $plane = Plane::create([
            'name' => 'Boeing 747',
            'max_seats' => 200,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->delete(route('planeDelete', $plane->id));

        $response->assertStatus(200)
            ->assertJson(['message' => 'Avión eliminado exitosamente']);

        $this->assertDatabaseMissing('planes', ['id' => $plane->id]);
    }

    public function test_destroy_plane_not_found()
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
        ])->delete(route('planeDelete', 9999));

        $response->assertStatus(404)
            ->assertJson(['message' => 'Avión no encontrado']);
    }

}
