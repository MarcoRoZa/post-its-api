<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use App\Models\Group;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{
    public function store(Request $request, Group $group)
    {
        $user = $request->user();
        if ($group->contains($user)) {
            $note = $user->notes()->create([
                'group_id' => $group->id,
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return new NoteResource($note);
        }

        return response()->json([], Response::HTTP_FORBIDDEN);
    }
}
