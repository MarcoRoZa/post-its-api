<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Models\Group;

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
}
