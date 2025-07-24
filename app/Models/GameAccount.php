<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameAccount extends Model
{
    /** @use HasFactory<\Database\Factories\GameAccountFactory> */
    use HasFactory, SoftDeletes;
   
    protected $fillable = [
        'user_id',
        'clan_id',
        'plarium_id',
        'player_name',
        'role',
        'stats',
        'type',
    ];

    protected $casts = [
        'stats' => 'array', // convierte JSON a array automáticamente
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clan():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
