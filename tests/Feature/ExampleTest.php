<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        config()->set('session.driver', 'database');

        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_the_login_page_loads_with_database_sessions(): void
    {
        config()->set('session.driver', 'database');

        $response = $this->get('/login');

        $response->assertOk();
    }
}
