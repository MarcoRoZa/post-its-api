<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Hash;
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

    public function testAUserCanBeLoggedIn()
    {
        factory(User::class)->create([
            'email' => "marco@test.com",
            'password' => Hash::make("123456"),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => "marco@test.com",
            'password' => "123456",
        ]);

        $response->assertOk();
    }
}
