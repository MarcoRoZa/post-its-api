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
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
