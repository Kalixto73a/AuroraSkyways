<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_index_returns_homeView()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        $response->assertViewIs('homeView');
    }
}
