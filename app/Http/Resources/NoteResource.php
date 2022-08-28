<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      schema="Note",
 *      @OA\Property(
 *          type="integer",
 *          description="ID de la nota.",
 *          property="id",
 *      ),
 *      @OA\Property(
 *          type="string",
 *          description="Título de la nota.",
 *          property="title",
 *      ),
 *      @OA\Property(
 *          type="string",
 *          description="Descripción de la nota.",
 *          property="description",
 *      ),
 * )
 */
class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
