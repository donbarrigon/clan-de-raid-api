<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'leader'       => new UserSummaryResource($this->whenLoaded('leader')),
            'type'         => $this->type,
            'description'  => $this->description,
            'created_at'   => $this->created_at->toDateTimeString(),
            'updated_at'   => $this->updated_at->toDateTimeString(),
            'deleted_at'   => $this->deleted_at->toDateTimeString(),
            'requeriments' => $this->whenLoaded('requeriments'),
            'members'      => $this->whenLoaded('members'),
        ];
    }
}
