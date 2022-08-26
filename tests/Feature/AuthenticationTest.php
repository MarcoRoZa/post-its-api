<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function testAUserCanBeRegistered()
    {
        $response = $this->postJson('/api/register', [
            'name' => "Marco",
            'email' => "marco@test.com",
            'password' => "123456",
        ]);

        $response->assertOk();
    }
}
