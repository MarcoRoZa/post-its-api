<?php

namespace Tests\Feature;

use App\Models\Group;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class GroupTest extends TestCase
{
    public function testAUserCanSeeExistingGroups()
    {
        Passport::actingAs(factory(User::class)->create());

        $response = $this->getJson('/api/groups');

        $response->assertOk();
        $response->assertJsonCount(Group::all()->count(), 'data');
    }
}
