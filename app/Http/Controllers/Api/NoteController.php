<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use App\Models\Group;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/groups/{uuid}/notes",
     *      summary="Listar notas.",
     *      tags={"notes"},
     *      @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="UUID del grupo.",
     *          required=true,
     *      ),
     *      @OA\Parameter(
     *          name="minDate",
     *          in="query",
     *          description="Fecha mínima de creación.",
     *          example="2022-08-29",
     *      ),
     *      @OA\Parameter(
     *          name="maxDate",
     *          in="query",
     *          description="Fecha máxima de creación.",
     *          example="2022-08-29",
     *      ),
     *      @OA\Parameter(
     *          name="images",
     *          in="query",
     *          description="Sólo incluir imágenes.",
     *          example="yes",
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Retorna la lista de notas filtradas.",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Note"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Grupo no encontrado.",
     *      ),
     *      security={{"bearerAuth":{}}},
     * )
     */
    public function index(Request $request, Group $group)
    {
        $notes = $group->notes();
        if ($request->minDate) $notes->whereDate('created_at', '>=', Carbon::parse($request->minDate . " 00:00:00"));
        if ($request->maxDate) $notes->whereDate('created_at', '<=', Carbon::parse($request->maxDate . " 23:59:59"));
        if ($request->images === 'yes') $notes->whereHas('files');

        return NoteResource::collection($notes->get());
    }

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
