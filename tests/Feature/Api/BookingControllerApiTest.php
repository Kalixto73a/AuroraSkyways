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

class BookingControllerApiTest extends TestCase
{
    use RefreshDatabase;
    public function test_all_flights_for_authenticated_user()
    {
        // Crear un usuario
        $user = User::create([
            'name' => 'Juan',
            'email' => 'juan@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        // Crear un avión
        $plane = Plane::create([
            'name' => 'Avión 1',
            'max_seats' => 100,
        ]);

        // Crear un vuelo
        $flight = Flight::create([
            'departure_date' => now()->addHour(2),
            'arrival_date' => now()->addHours(3),
            'origin' => 'Madrid',
            'destination' => 'Barcelona',
            'plane_id' => $plane->id,
            'available' => true,
        ]);

        // Crear una reserva (booking) asociada al vuelo
        $booking = Booking::create([
            'user_id' => $user->id,
            'flight_id' => $flight->id,
            'plane_id' => $plane->id,
            'seat_number' => '15',
            'status' => 'Activo',
        ]);

        // Autenticación usando el JWT del usuario
        $token = JWTAuth::fromUser($user);

        // Realizar la solicitud GET a la ruta 'flights' (ajusta la ruta según la configuración de tu API)
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(route('allBookings')); // Cambia 'flights.all' por la ruta correspondiente

        // Verificar que la respuesta sea exitosa
        $response->assertStatus(200);

        // Verificar que la respuesta contiene los vuelos que el usuario ha reservado
        $response->assertJsonFragment([
            'id' => $flight->id,
            'origin' => $flight->origin,
            'destination' => $flight->destination,
            // Otros campos que desees verificar
        ]);

        // Verificar que se ha encontrado el vuelo reservado
        $this->assertDatabaseHas('flights', [
            'id' => $flight->id,
            'origin' => $flight->origin,
            'destination' => $flight->destination,
        ]);
    }

    public function test_user_can_store_booking()
    {
        $user = User::create([
            'name' => 'Juan',
            'email' => 'juan@example.com',
            'password' => bcrypt('password'),
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

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            ])->post(route('createBooking'), [
                'user_id' => $user->id,
                'flight_id' => $flight->id,
                'plane_id' => $plane->id,
                'seat_number' => '15',
                'status' => 'Activo',
                ]);
        $response->assertStatus(201);
    }

    public function test_show_booking_successfully()
    {
        $user = User::create([
            'name' => 'Juan',
            'email' => 'juan@example.com',
            'password' => bcrypt('password'),
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
            'id' => '1',
            'user_id' => $user->id,
            'flight_id' => $flight->id,
            'plane_id' => $plane->id,
            'seat_number' => '15',
            'status' => 'Activo',
        ]);

        $token = JWTAuth::fromUser($user);
        // Hacer la solicitud POST para crear la reserva
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson(route('bookingShow', '1'));

        $response->assertStatus(200);
    }

    public function test_show_booking_unauthenticated_user()
    {
        $user1 = User::create([
            'name' => 'Juan',
            'email' => 'juan@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $user2 = User::create([
            'name' => 'Juan',
            'email' => 'juan@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
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
            'id' => '1',
            'user_id' => $user1->id,
            'flight_id' => $flight->id,
            'plane_id' => $plane->id,
            'seat_number' => 'A1',
            'status' => 'Activo',
        ]);

        $token = JWTAuth::fromUser($user2);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson(route('bookingShow','1'));
                         
        $response->assertJson([
            'message' => 'Acceso denegado. Se requiere permiso de usuario',
            'message' => 'Reserva no encontrada o no autorizada'
        ], 403);
    }
    public function testUpdateBookingSuccessfully()
    {
        $user = User::create([
            'name' => 'Juan',
            'email' => 'juan@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
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
            'id' => '1',
            'user_id' => $user->id,
            'flight_id' => $flight->id,
            'plane_id' => $plane->id,
            'seat_number' => 'A1',
            'status' => 'Activo',
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson(route('bookingUpdate', '1'), [
            'flight_id' => $flight->id,
            'status' => 'Inactivo',
        ]);

        // Verificar que la respuesta es exitosa
        $response->assertJson([
            'message' => 'Reserva actualizada correctamente',
            'booking' => [
                'status' => 'Inactivo',
            ],
        ], 200);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson(route('bookingUpdate', '2'), [
            'flight_id' => $flight->id,
            'status' => 'Inactivo',
        ]);

        $response->assertStatus(403);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson(route('bookingUpdate', '1'), [
            'flight_id' => $flight->id,
            'status' => 'Inactivo',
        ]);
        
        $response->assertJson([
            'message' => 'No se realizaron cambios',
            'booking' => [
                'status' => 'Inactivo',
            ],
        ], 200);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'Inactivo',
        ]);
    }
    public function testDestroyBookingSuccessfully()
    {
        $user = User::create([
            'name' => 'Juan',
            'email' => 'juan@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
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
            'id' => '1',
            'user_id' => $user->id,
            'flight_id' => $flight->id,
            'plane_id' => $plane->id,
            'seat_number' => 'A1',
            'status' => 'Activo',
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'user_id' => $user->id,
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson(route('bookingDelete', '1'));

        $response->assertJson([
            'message' => 'Reserva eliminada correctamente',
        ], 200);

        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);
    }
    public function testDestroyBookingForbidden()
    {
        $user1 = User::create([
            'name' => 'Juan',
            'email' => 'juan@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $user2 = User::create([
            'name' => 'Juan2',
            'email' => 'juan2@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
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
            'id' => '1',
            'user_id' => $user1->id,
            'flight_id' => $flight->id,
            'plane_id' => $plane->id,
            'seat_number' => 'A1',
            'status' => 'Activo',
        ]);

        $token = JWTAuth::fromUser($user2);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson(route('bookingDelete', '1'));

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Reserva no encontrada o no tienes permiso',
        ]);
    }
}
