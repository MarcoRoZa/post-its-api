<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\GroupUser;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class NoteTest extends TestCase
{
    public function testAUserCanCreateNoteWithTitleAndDescription()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $group = Group::query()->firstOrFail();

        GroupUser::query()->firstOrCreate([
            'group_id' => $group->id,
            'user_id' => $user->id,
        ]);

        $response = $this->post("/api/groups/{$group->uuid}/notes", [
            'title' => "Mi título",
            'description' => "Descripción de una nota.",
        ]);

        $response->assertCreated();
        $response->assertJsonStructure(['title', 'description']);
    }
}
