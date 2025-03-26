<?php

namespace Tests\Feature\Middleware;

use Tests\TestCase;
use App\Models\User;
use App\Models\Plane;
use App\Models\Flight;
use App\Models\Booking;
use Illuminate\Foundation\Testing\WithFaker;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class isAdminTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_allows_user_with_role_admin_to_pass()
    {
        $user = User::create([
            'name' => 'Juan',
            'email' => 'juan@example.com',
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
            'available' => true,
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'plane_id' => $plane->id,
            'flight_id' => $flight->id,
            'seat_number' => 'A1',
            'status' => 'Activo',
        ]);
    
        $token = JWTAuth::fromUser($user);
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(route('allPlanes'));
 
        $response->assertStatus(200);
    } 
    public function test_access_denied_for_user_or_non_user_role()
    {
        $user = User::create([
            'name' => 'Raul',
            'email' => 'Raul@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->get(route('allPlanes')); 

        $response->assertStatus(403);

        $response->assertJson(['message' => 'Acceso denegado. Se requiere permiso de administrador.']);
    }
    public function test_access_redirect_for_user_or_non_user_role()
    {
        $user = User::create([
            'name' => 'Raul',
            'email' => 'Raul@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            
        ])->get(route('allPlanes')); 
        
        $response->assertRedirect('/login');
    }
}
