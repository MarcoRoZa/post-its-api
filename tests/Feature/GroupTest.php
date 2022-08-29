<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\GroupUser;
use Tests\AuthTestCase;

class GroupTest extends AuthTestCase
{
    public function testAUserCanSeeExistingGroups()
    {
        $response = $this->getJson('/api/groups');

        $response->assertOk();
        $response->assertJsonCount(Group::all()->count());
    }

    public function testAUserCanJoinToExistingGroup()
    {
        $group = Group::query()->firstOrFail();

        $response = $this->getJson("/api/groups/$group->uuid/join");

        $response->assertOk();
        $response->assertJsonStructure(['notes']);
        $this->assertNotEmpty(GroupUser::query()->where('user_id', $this->authUser->id)->where('group_id', $group->id)->get());
    }

    public function testAUserCanSeeNotesOfAJoinedGroup()
    {
        $group = factory(Group::class)->create();

        GroupUser::query()->firstOrCreate([
            'group_id' => $group->id,
            'user_id' => $this->authUser->id,
        ]);

        $this->authUser->notes()->create([
            'group_id' => $group->id,
            'title' => "Title",
            'description' => "Description",
        ]);

        $response = $this->getJson("/api/groups/$group->uuid");

        $response->assertOk();
        $response->assertJsonCount(1, 'notes');
    }
}
