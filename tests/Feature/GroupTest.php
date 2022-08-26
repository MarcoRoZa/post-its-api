<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\GroupUser;
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
        $response->assertJsonCount(Group::all()->count());
    }

    public function testAUserCanJoinToExistingGroup()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $group = Group::query()->firstOrFail();

        $response = $this->getJson("/api/groups/$group->uuid/join");

        $response->assertOk();
        $response->assertJsonStructure(['notes']);
        $this->assertNotEmpty(GroupUser::query()->where('user_id', $user->id)->where('group_id', $group->id)->get());
    }
}
