<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * @OA\Get (
     *      path="/api/groups",
     *      summary="Obtener lista de grupos existentes",
     *      tags={"groups"},
     *      @OA\Response(
     *          response=200,
     *          description="Retorna la lista de grupos existentes.",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Group"),
     *          ),
     *      ),
     *      security={{"bearerAuth":{}}},
     * )
     */
    public function index()
    {
        return GroupResource::collection(Group::all());
    }

    public function join(Request $request, Group $group)
    {
        GroupUser::query()->firstOrCreate([
            'group_id' => $group->id,
            'user_id' => $request->user()->id,
        ]);

        return new GroupResource($group);
    }
}
