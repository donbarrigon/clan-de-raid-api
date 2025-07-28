<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'                 => $this->id,
            'name'               => $this->name,
            'email'              => $this->email,
            'email_verified_at'  => $this->email_verified_at,
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
            'deleted_at'         => $this->deleted_at,
            'profile_id'         => $profile->id,
            'full_name'          => $profile->full_name,
            'phone_number'       => $profile->phone_number,
            'discord_username'   => $profile->discord_username,
            'city_id'            => $profile->city_id,
            'preferences'        => $profile->preferences,
            'profile_created_at' => $profile->created_at,
            'profile_updated_at' => $profile->updated_at,
            'profile_deleted_at' => $profile->deleted_at,
            'city'               => $city->name && $state->name && $country->name
                ? "{$city->name}, {$state->name}, {$country->name}"
                : "unknown",
            'led_clans' => ClanResource::collection($this->ledClans),
            'game_accounts' => GameAccountResource::collection($this->gameAccounts),
        ];
    }
}
