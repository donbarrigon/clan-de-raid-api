<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $city    = optional(optional($this->profile)->city);
        $state   = optional($city->state);
        $country = optional($city->country);
        return [
            'id'               => $this->id,
            'nick'             => $this->name,
            'full_name'        => optional($this->profile)->full_name,
            'phone_number'     => optional($this->profile)->phone_number,
            'discord_username' => optional($this->profile)->discord_username,
            'city'             => $city->name . ', ' . $state->name . ', ' . $country->name,
        ];
    }
}
