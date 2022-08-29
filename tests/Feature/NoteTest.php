<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Note;
use App\Notifications\NoteCreation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\AuthTestCase;

class NoteTest extends AuthTestCase
{
    private $newGroup;

    protected function setUp(): void
    {
        parent::setUp();

        $this->newGroup = Group::query()->firstOrFail();

        GroupUser::query()->firstOrCreate([
            'group_id' => $this->newGroup->id,
            'user_id' => $this->authUser->id,
        ]);
    }

    public function testAUserCanCreateNoteWithTitleAndDescription()
    {
        Notification::fake();

        $response = $this->postJson("/api/groups/{$this->newGroup->uuid}/notes", [
            'title' => "Mi título",
            'description' => "Descripción de una nota.",
        ]);

        $response->assertCreated();
        $response->assertJsonStructure(['title', 'description']);
        Notification::assertSentTo($this->newGroup->users, NoteCreation::class);
    }

    public function testAUserCanCreateNoteAttachingImages()
    {
        Storage::fake();

        $images = [
            UploadedFile::fake()->image('image1.png'),
            UploadedFile::fake()->image('image2.jpg')
        ];

        $response = $this->postJson("/api/groups/{$this->newGroup->uuid}/notes", [
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
        $dateSlots = [
            '2022-06-28',
            '2022-07-28',
            '2022-08-28',
        ];

        foreach ($dateSlots as $dateSlot) {
            factory(Note::class, 5)->create([
                'user_id' => $this->authUser->id,
                'group_id' => $this->newGroup->id,
                'created_at' => $dateSlot,
            ]);
        }

        $response = $this->json('GET', "/api/groups/{$this->newGroup->uuid}/notes", [
            'minDate' => '2022-07-01',
            'maxDate' => '2022-07-31',
        ]);

        $response->assertOk();
        $response->assertJsonCount(5);
    }

    public function testAUserCanSeeNotesOnlyWithImagesAttached()
    {
        factory(Note::class, 2)->create([
            'user_id' => $this->authUser->id,
            'group_id' => $this->newGroup->id,
        ]);

        $this->newGroup->notes()->create(factory(Note::class)->make()->toArray())
            ->files()
            ->createMany(factory(File::class, 3)->make()->toArray());

        $response = $this->json('GET', "/api/groups/{$this->newGroup->uuid}/notes", [
            'images' => 'yes',
        ]);

        $response->assertOk();
        $response->assertJsonCount(1);
        $response->assertJsonCount(3, '0.files');
    }
}
