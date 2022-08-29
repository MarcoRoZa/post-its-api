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
        return GroupResource::collection(Group::with(['notes.files', 'users'])->get());
    }

    /**
     * @OA\Get (
     *      path="/api/groups/{uuid}",
     *      summary="Ver el contenido de un grupo.",
     *      tags={"groups"},
     *      @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="UUID del grupo.",
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Retorna el grupo y su contenido.",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/Group",
     *          ),
     *      ),
     *      security={{"bearerAuth":{}}},
     * )
     */
    public function show(Group $group)
    {
        return new GroupResource($group);
    }

    /**
     * @OA\Get (
     *      path="/api/groups/{uuid}/join",
     *      summary="Unirse a un grupo existente.",
     *      tags={"groups"},
     *      @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="UUID del grupo a unirse.",
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Retorna el grupo y su contenido.",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/Group",
     *          ),
     *      ),
     *      security={{"bearerAuth":{}}},
     * )
     */
    public function join(Request $request, Group $group)
    {
        GroupUser::query()->firstOrCreate([
            'group_id' => $group->id,
            'user_id' => $request->user()->id,
        ]);

        return new GroupResource($group);
    }
}
