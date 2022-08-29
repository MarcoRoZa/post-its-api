<?php

namespace Tests;

use App\User;
use Laravel\Passport\Passport;

abstract class AuthTestCase extends TestCase
{
    protected $authUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authUser = factory(User::class)->create();
        Passport::actingAs($this->authUser);
    }
}
