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
        // Crear usuario admin para autenticación
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Generar un token para autenticación
        $token = JWTAuth::fromUser($user);

        // Datos del avión
        $planeData = [
            'name' => 'Boeing 747',
            'max_seats' => 200,
        ];

        // Realizar la petición con el token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->post(route('createPlanes'), $planeData);

        // Asegurar que el avión se creó correctamente
        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Avión creado exitosamente',
                'plane' => [
                    'name' => 'Boeing 747',
                    'max_seats' => 200,
                ],
            ]);

        // Verificar que el avión está en la base de datos
        $this->assertDatabaseHas('planes', [
            'name' => 'Boeing 747',
            'max_seats' => 200,
        ]);
    }
    public function test_show_returns_plane_with_flights_and_bookings()
    {
        // Crear usuario admin para autenticación
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Generar un token JWT
        $token = JWTAuth::fromUser($user);

        // Crear un avión
        $plane = Plane::create([
            'name' => 'Boeing 747',
            'max_seats' => 200,
        ]);

        // Crear un vuelo asociado al avión
        $flight = Flight::create([
            'departure_date' => now()->addDay(),
            'arrival_date' => now()->addDays(2),
            'origin' => 'Madrid',
            'destination' => 'Barcelona',
            'plane_id' => $plane->id,
            'available' => true,
        ]);

        // Crear un usuario pasajero
        $passenger = User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // Crear una reserva asociada al vuelo y al usuario
        $booking = Booking::create([
            'user_id' => $passenger->id,
            'plane_id' => $plane->id,
            'flight_id' => $flight->id,
            'seat_number' => 'A1',
            'status' => 'Activo',
        ]);

        // Realizar la solicitud autenticada
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->get(route('planeShow', $plane->id));

        // Verificar que la respuesta sea exitosa
        $response->assertJsonFragment([
            'id' => $plane->id,
            'name' => 'Boeing 747',
            'max_seats' => (string) $plane->max_seats, // Convierte el número a string
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
        ])->get(route('planeShow', 999)); // ID que no existe

        $response->assertStatus(404)
            ->assertJson(['message' => 'Avión no encontrado']);
    }

    public function test_update_plane_successfully()
    {
        // Crear usuario admin para autenticación
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Generar token JWT
        $token = JWTAuth::fromUser($user);

        // Crear un avión
        $plane = Plane::create([
            'name' => 'Boeing 747',
            'max_seats' => 200,
        ]);

        // Datos para la actualización
        $updateData = [
            'name' => 'Airbus A380',
            'max_seats' => 300,
        ];

        // Hacer la petición PUT autenticada
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->put(route('planeUpdate', $plane->id), $updateData);

        // Verificar que la respuesta sea 200 OK
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Avión actualizado exitosamente',
                'plane' => [
                    'id' => $plane->id,
                    'name' => 'Airbus A380',
                    'max_seats' => 300,
                ],
            ]);

        // Verificar en la base de datos que los datos fueron actualizados
        $this->assertDatabaseHas('planes', [
            'id' => $plane->id,
            'name' => 'Airbus A380',
            'max_seats' => 300,
        ]);
    }

    public function test_update_plane_not_found()
    {
        // Crear usuario admin para autenticación
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Generar token JWT
        $token = JWTAuth::fromUser($user);

        // Hacer la petición PUT a un ID inexistente
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->put(route('planeUpdate', 9999), [
            'name' => 'Avión Fantasma',
            'max_seats' => 500,
        ]);

        // Verificar que la respuesta sea 404
        $response->assertStatus(404)
            ->assertJson(['message' => 'Avión no encontrado']);
    }

    public function test_destroy_plane_successfully()
    {
        // Crear usuario admin para autenticación
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Generar token JWT
        $token = JWTAuth::fromUser($user);

        // Crear un avión
        $plane = Plane::create([
            'name' => 'Boeing 747',
            'max_seats' => 200,
        ]);

        // Hacer la petición DELETE autenticada
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->delete(route('planeDelete', $plane->id));

        // Verificar que la respuesta sea 200 OK
        $response->assertStatus(200)
            ->assertJson(['message' => 'Avión eliminado exitosamente']);

        // Verificar que el avión ya no está en la base de datos
        $this->assertDatabaseMissing('planes', ['id' => $plane->id]);
    }

    public function test_destroy_plane_not_found()
    {
        // Crear usuario admin para autenticación
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Generar token JWT
        $token = JWTAuth::fromUser($user);

        // Hacer la petición DELETE a un ID inexistente
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->delete(route('planeDelete', 9999));

        // Verificar que la respuesta sea 404
        $response->assertStatus(404)
            ->assertJson(['message' => 'Avión no encontrado']);
    }

}
