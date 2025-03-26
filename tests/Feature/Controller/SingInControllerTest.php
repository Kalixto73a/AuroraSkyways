<?php

namespace Tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SingInControllerTest extends TestCase
{
    public function test_show_register_form_view()
    {
        $response = $this->get(route('register')); 

        $response->assertStatus(200);

        $response->assertViewIs('singInView');
    }
}
