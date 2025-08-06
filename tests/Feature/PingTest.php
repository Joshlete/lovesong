<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Feature Tests - Test complete user workflows or application features end-to-end
 * 
 * Use Feature tests for:
 * - HTTP endpoints and API routes
 * - Complete user workflows (registration, login, checkout)
 * - Integration between multiple components
 * - Database interactions with real data
 * - Testing with real dependencies (database, external services)
 * 
 * Examples:
 * - Testing API endpoints return correct responses
 * - User can register and login
 * - Complete e-commerce checkout flow
 * - File upload and processing workflows
 */
class PingTest extends TestCase
{
    /**
     * Test that the ping endpoint returns the correct response.
     * This is a feature test because it makes a real HTTP request
     * through Laravel's routing system.
     */
    public function test_ping_endpoint_returns_pong(): void
    {
        $this->get('/ping')
            ->assertStatus(200)
            ->assertSee('pong');
    }
}
