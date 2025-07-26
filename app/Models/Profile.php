<?php

namespace App\Models;

use App\Traits\HasHistoryObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nnjeim\World\Models\City;

class Profile extends Model
{
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory, SoftDeletes, HasHistoryObserver;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone_number',
        'discord_username',
        'city_id',
        'preferences',
    ];

    protected $casts = [
        'preferences' => 'array', // convierte JSON a array automáticamente
    ];

    public function user (): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): HasOne
    {
        return $this->hasOne(City::class);
    }
    
}
