<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use App\Models\Group;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{
    /**
     * @OA\Post (
     *      path="/api/groups/{uuid}/notes",
     *      summary="Crear una nota.",
     *      tags={"notes"},
     *      @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="UUID del grupo.",
     *          required=true,
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="title",
     *                      type="string",
     *                      default="Mi título",
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      default="Mi descriptión",
     *                  ),
     *                  @OA\Property(
     *                      property="images[]",
     *                      type="array",
     *                      @OA\Items(type="file", format="binary")
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Retorna la nota creada.",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/Note",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Creación no permitida.",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Grupo no encontrado.",
     *      ),
     *      security={{"bearerAuth":{}}},
     * )
     */
    public function store(Request $request, Group $group)
    {
        $user = $request->user();
        if ($group->contains($user)) {
            try {
                DB::beginTransaction();

                $note = $user->notes()->create([
                    'group_id' => $group->id,
                    'title' => $request->title,
                    'description' => $request->description,
                ]);

                if ($request->images) {
                    foreach ($request->images as $upload) {
                        if ($upload) {
                            $image = $note->files()->create([
                                'hash' => $upload->hashName(),
                                'name' => $upload->getClientOriginalName(),
                            ]);

                            Storage::disk()->put($image->path(), file_get_contents($upload));
                        }
                    }
                }

                DB::commit();

                return new NoteResource($note);
            }
            catch (Exception $exception) {
                DB::rollBack();

                return response()->json([], Response::HTTP_BAD_REQUEST);
            }
        }

        return response()->json([], Response::HTTP_FORBIDDEN);
    }
}
