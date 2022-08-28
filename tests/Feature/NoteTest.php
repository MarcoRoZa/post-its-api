<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Note;
use App\Notifications\NoteCreation;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
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

        Notification::fake();

        $response = $this->post("/api/groups/{$group->uuid}/notes", [
            'title' => "Mi título",
            'description' => "Descripción de una nota.",
        ]);

        $response->assertCreated();
        $response->assertJsonStructure(['title', 'description']);
        Notification::assertSentTo($group->users, NoteCreation::class);
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

    public function testAUserCanSeeFilteredNotesByCreationDate()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $group = factory(Group::class)->create();

        GroupUser::query()->firstOrCreate([
            'group_id' => $group->id,
            'user_id' => $user->id,
        ]);

        $dateSlots = [
            '2022-06-28',
            '2022-07-28',
            '2022-08-28',
        ];

        foreach ($dateSlots as $dateSlot) {
            factory(Note::class, 5)->create([
                'user_id' => $user->id,
                'group_id' => $group->id,
                'created_at' => $dateSlot,
            ]);
        }

        $response = $this->json('GET', "/api/groups/$group->uuid/notes", [
            'minDate' => '2022-07-01',
            'maxDate' => '2022-07-31',
        ]);

        $response->assertOk();
        $response->assertJsonCount(5);
    }

    public function testAUserCanSeeNotesOnlyWithImagesAttached()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $group = factory(Group::class)->create();

        GroupUser::query()->firstOrCreate([
            'group_id' => $group->id,
            'user_id' => $user->id,
        ]);

        factory(Note::class, 2)->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);

        $group->notes()->create(factory(Note::class)->make()->toArray())
            ->files()
            ->createMany(factory(File::class, 3)->make()->toArray());

        $response = $this->json('GET', "/api/groups/$group->uuid/notes", [
            'images' => 'yes',
        ]);

        $response->assertOk();
        $response->assertJsonCount(1);
        $response->assertJsonCount(3, '0.files');
    }
}
