<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Plane;
use App\Models\Flight;
use App\Models\Booking;
use Illuminate\Foundation\Testing\WithFaker;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanBookFlight()
    {
        $user = User::create([
            'name' => 'Juan',
            'email' => 'juan@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
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

 
        $bookings = Booking::where('user_id', $user->id)->get();

        $response = $this->post(route('webLogin'), [
            'email' => $user->email,
            'password' => 'password',
        ]);


        $response = $this->get(route('bookings'));

  
        $response->assertStatus(200);

        foreach ($bookings as $booking) {
            $response->assertSee($booking->id);
        }
    }

    public function test_store_booking_and_update_flight_status()
    {
        $user = User::create([
            'name' => 'Juan',
            'email' => 'juan@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
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

        $data = [
            'flight_id' => $flight->id,
            'plane_id' => $plane->id,
            'seat_number' => '15',
            'status' => 'Activo',
        ];

        $response = $this->post(route('webLogin'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->post(route('saveBookings'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Reserva realizada correctamente');
        
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'flight_id' => $flight->id,
            'seat_number' => '15',
            'status' => 'Activo',
        ]);

        $this->assertTrue($flight->available); 
    }
}
