<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\GroupUser;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function testAUserCanCreateNoteAttachingImages()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $group = Group::query()->firstOrFail();

        GroupUser::query()->firstOrCreate([
            'group_id' => $group->id,
            'user_id' => $user->id,
        ]);

        Storage::fake();

        $images = [
            UploadedFile::fake()->image('image1.png'),
            UploadedFile::fake()->image('image2.jpg')
        ];

        $response = $this->post("/api/groups/$group->uuid/notes", [
            'title' => "Mi título",
            'description' => "Descripción de una nota.",
            'images' => $images,
        ]);

        $response->assertCreated();
        $response->assertJsonCount(2, 'files');
        Storage::disk()->assertExists("files/" . $images[0]->hashName());
        Storage::disk()->assertExists("files/" . $images[1]->hashName());
    }
}
