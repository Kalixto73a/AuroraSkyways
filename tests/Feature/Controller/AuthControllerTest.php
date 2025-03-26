<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_successfully()
    {
        $response = $this->postJson(route('singIn'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
    
        $response->assertStatus(201)
                 ->assertJsonStructure(['user', 'token']);
    
        $this->assertDatabaseHas('users', ['email' => 'johndoe@example.com']);
    }    
    public function test_register_fails_if_email_already_exists()
    {
        $user = User::create([
            'name' => 'Juan',
            'email' => 'existing@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->postJson(route('singIn'), [
            'name' => 'John Doe',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(400);
    }
    public function test_register_fails_with_invalid_data()
    {
        $response = $this->postJson(route('singIn'), [
            'name' => '', 
            'email' => 'invalid-email', 
            'password' => '123', 
            'password_confirmation' => '1234', 
        ]);

        $response->assertStatus(400);
    }
    public function test_register_redirects_to_home_for_web_requests()
    {
        $response = $this->post(route('singIn'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
    
        $response->assertRedirect(route('home'));
    }    
    public function test_successful_login()
    {
        $user = User::create([
            'name' => 'Juan',
            'email' => 'existing@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('logIn'), [
            'email' => 'existing@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'token'
                ]);
    }

    public function test_failed_login()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('logIn'), [
            'email' => 'wrong@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(401)
                ->assertJson(['message' => 'Las credenciales no coinciden con nuestros registros.']);
    }
    public function test_login_redirects_when_not_json_request()
    {
        $user = User::create([
            'name' => 'Juan',
            'email' => 'existing@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    
        $response = $this->post(route('logIn'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);
    
        $response->assertRedirect(route('home'));
    }
    public function test_web_login_redirects_on_success()
    {
        $user = User::create([
            'name' => 'Juan',
            'email' => 'existing@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $response = $this->post(route('webLogin'), [
            'email' => 'existing@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
    }

    public function test_web_login_fails_with_wrong_credentials()
    {
        $user1 = User::create([
            'name' => 'Juan',
            'email' => 'existing@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $response = $this->post(route('webLogin'), [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect();

        $response->assertSessionHasErrors(['email' => 'El correo es incorrecto.']);

        $user2 = User::create([
            'name' => 'Juan',
            'email' => 'test@example.com',
            'password' => bcrypt('password1'),
            'role' => 'user',
        ]);

        $response = $this->post(route('webLogin'), [
            'email' => $user2->email,
            'password' => 'password',
        ]);

        $response->assertRedirect();

        $response->assertSessionHasErrors(['password' => 'La contraseña es incorrecta.']);
    }

    public function test_logout_with_no_token()
    {
    
        $response = $this->postJson(route('logout'));
    
        $response->assertStatus(401)
                 ->assertJson(['error' => 'Token no proporcionado']);
    }
    
    public function test_logout_success_json()
    {
        $user = User::create([
            'name' => 'Juan',
            'email' => 'existing@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson(route('logout')); 

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Cierre de sesión exitoso'
                ]);
    }

    public function test_logout_with_expired_token()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        // Forzar la expiración del token
        JWTAuth::setToken($token)->invalidate();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson(route('logout'));

        $response->assertStatus(401)
                ->assertJson(['error' => 'Token inválido']);
    }

    public function test_logout_success_redirect()
    {
        $user = User::create([
            'name' => 'Juan',
            'email' => 'existing@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('webLogout'));

        $response->assertRedirect('/');
    }
    public function test_logout_invalid_token_web_redirect()
    {
        $invalidToken = 'invalid-token';

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $invalidToken,
        ])->post(route('webLogout'));

        $response->assertRedirect('/');
    }
    public function test_refresh_token()
    {
        $user = User::create([
            'name' => 'Juan',
            'email' => 'existing@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('refresh'));  

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
        ]);

        $newToken = $response->json()['access_token'];
        $this->assertNotEquals($token, $newToken);
    }
    public function test_webLogout_with_exception()
    {
        Auth::shouldReceive('guard')->once()->andThrow(new \Exception('Error inesperado'));

        $response = $this->post(route('webLogout'));

        $response->assertRedirect('/')
                ->assertSessionHas('error', 'Error al cerrar sesión');
    }
}
