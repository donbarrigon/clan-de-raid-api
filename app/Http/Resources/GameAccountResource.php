<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $clan = optional($this->clan);
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'clan' => ClanSummaryResource::make($clan),
            'plarium_id' => $this->plarium_id,
            'player_name' => $this->player_name,
            'role' => $this->role,
            'stats' => $this->stats ? json_decode($this->stats, true) : [],
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
