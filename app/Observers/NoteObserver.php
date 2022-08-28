<?php

namespace App\Observers;

use App\Models\Note;
use App\Notifications\NoteCreation;
use Illuminate\Support\Facades\Notification;

class NoteObserver
{
    /**
     * Handle the note "created" event.
     *
     * @param Note $note
     * @return void
     */
    public function created(Note $note)
    {
        $users = $note->group->users;
        Notification::send($users, (new NoteCreation($note)));
    }
}
