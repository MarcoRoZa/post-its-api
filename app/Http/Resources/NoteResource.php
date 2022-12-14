<?php

namespace App\Http\Resources;

use App\Models\Note;
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
 *      @OA\Property(
 *          type="array",
 *          description="Lista de imágenes de la nota.",
 *          property="files",
 *          @OA\Items(ref="#/components/schemas/File"),
 *      ),
 *      @OA\Property(
 *          type="string",
 *          description="Fecha de creación de la nota.",
 *          property="created",
 *      ),
 * )
 */
class NoteResource extends JsonResource
{
    public function __construct(Note $note)
    {
        parent::__construct($note);

        $note->loadMissing(['files']);
    }

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
            'files' => FileResource::collection($this->files),
            'created' => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}
