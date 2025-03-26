<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    public function test_show_login_form_view()
    {
        $response = $this->get(route('login')); 
    
        $response->assertStatus(200);
    
        $response->assertViewIs('loginView');
    }
    
}
