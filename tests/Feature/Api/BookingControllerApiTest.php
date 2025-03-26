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
    public function test_store_reservation_successfully()
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

        $data = [
            'flight_id'   => $flight->id,
            'plane_id'    => $plane->id,
            'seat_number' => '12A',
            'status'      => 'Activo',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->post(route('createBooking'), $data);

        $response->assertStatus(201);

        $response->assertJson([
            'message' => 'Reserva creada exitosamente',
        ]);

        $this->assertDatabaseHas('bookings', [
            'user_id'     => $user->id,
            'flight_id'   => $flight->id,
            'plane_id'    => $plane->id,
            'seat_number' => '12A',
            'status'      => 'Activo',
        ]);
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
            'seat_number' => 'A1',
            'status' => 'Activo',
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->get(route('bookingShow', '1'));

        $response->assertStatus(200);

        $response->assertJson([
            'booking' => [
                'id' => $booking->id,
                'user_id' => $user->id,
            ]
        ]);
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
