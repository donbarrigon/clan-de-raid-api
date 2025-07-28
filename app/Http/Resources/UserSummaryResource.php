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
        $profile = optional($this->profile);
        $city    = optional($profile->city);
        $state   = optional($city->state);
        $country = optional($city->country);

        return [
            'id'               => $this->id,
            'nick'             => $this->name,
            'full_name'        => $profile->full_name,
            'phone_number'     => $profile->phone_number,
            'discord_username' => $profile->discord_username,
            'city_id'          => $profile->city_id,
            'city'             => $city->name && $state->name && $country->name
                ? "{$city->name}, {$state->name}, {$country->name}"
                : "unknown",
        ];

    }
}
