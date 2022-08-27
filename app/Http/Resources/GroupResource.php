<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      schema="Group",
 *      @OA\Property(
 *          type="string",
 *          description="UUID del grupo.",
 *          property="uuid",
 *      ),
 *      @OA\Property(
 *          type="string",
 *          description="Nombre del grupo.",
 *          property="name",
 *      ),
 *      @OA\Property(
 *          type="string",
 *          description="Descripción del grupo.",
 *          property="description",
 *      ),
 *      @OA\Property(
 *          type="array",
 *          description="Lista de notas cuando está unido al grupo.",
 *          property="notes",
 *          @OA\Items(ref="#/components/schemas/Note"),
 *      ),
 * )
 */
class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $request->user();

        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'notes' => $this->when($user && $this->contains($user), function () {
                return NoteResource::collection($this->notes);
            }),
        ];
    }
}
