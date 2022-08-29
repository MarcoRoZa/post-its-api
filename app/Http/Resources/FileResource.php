<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      schema="File",
 *      @OA\Property(
 *          type="string",
 *          description="URL de la imagen.",
 *          property="url",
 *      ),
 *      @OA\Property(
 *          type="string",
 *          description="Nombre de la imagen.",
 *          property="name",
 *      ),
 * )
 */
class FileResource extends JsonResource
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
            'url' => asset($this->path()),
            'name' => $this->name,
        ];
    }
}
