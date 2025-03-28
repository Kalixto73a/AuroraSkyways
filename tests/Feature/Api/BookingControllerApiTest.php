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
            'departure_date' => now()->addHour(2),
            'arrival_date' => now()->addHours(3),
            'origin' => 'Madrid',
            'destination' => 'Barcelona',
            'plane_id' => $plane->id,
            'available' => true,
        ]);

        
        $booking = Booking::create([
            'user_id' => $user->id,
            'flight_id' => $flight->id,
            'plane_id' => $plane->id,
            'seat_number' => '15',
            'status' => 'Activo',
        ]);

        
        $token = JWTAuth::fromUser($user);

        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(route('allBookingsFromUser')); 

        
        $response->assertStatus(200);

        
        $response->assertJsonFragment([
            'id' => $flight->id,
            'origin' => $flight->origin,
            'destination' => $flight->destination,
        ]);

        
        $this->assertDatabaseHas('flights', [
            'id' => $flight->id,
            'origin' => $flight->origin,
            'destination' => $flight->destination,
        ]);
    }

    public function test_error_user_dont_have_bookings()
    {
        $user = User::create([
            'name' => 'Juan',
            'email' => 'juan@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $token = JWTAuth::fromuser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(route('allBookingsFromUser')); 

        $response->assertJson(['message' => 'No hay vuelos reservados'], 404);
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
    public function test_user_cant_store_2_or_more_bookings_for_the_same_flight()
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
            'user_id' => $user->id,
            'flight_id' => $flight->id,
            'plane_id' => $plane->id,
            'seat_number' => '15',
            'status' => 'Activo',
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
        $response->assertJson(['message' => 'Ya tienes una reserva en este vuelo'], 400);
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
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson(route('bookingShow', '1'));

        $response->assertStatus(200);
    }

    public function test_show_booking_with_no_booking_or_other_user_booking()
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
            'seat_number' => 'A1',
            'status' => 'Activo',
        ]);

        $token = JWTAuth::fromUser($user2);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson(route('bookingShow','1'));
                         
        $response->assertJson([
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
            'plane_id' => $plane->id,
            'origin' => 'Madrid',
            'destination' => 'Barcelona',
            'available' => true,
        ]);

        $booking = Booking::create([
            'id' => '1',
            'user_id' => $user->id,
            'flight_id' => $flight->id,
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
            'plane_id' => $plane->id,
            'origin' => 'Madrid',
            'destination' => 'Barcelona',
            'available' => true,
        ]);

        $booking = Booking::create([
            'id' => '1',
            'user_id' => $user->id,
            'flight_id' => $flight->id,
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
            'plane_id' => $plane->id,
            'origin' => 'Madrid',
            'destination' => 'Barcelona',
            'available' => true,
        ]);

        $booking = Booking::create([
            'id' => '1',
            'user_id' => $user1->id,
            'flight_id' => $flight->id,
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
