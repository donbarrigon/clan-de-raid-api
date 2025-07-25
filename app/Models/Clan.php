<?php

namespace App\Models;

use App\Traits\HasHistoryObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clan extends Model
{
    /** @use HasFactory<\Database\Factories\ClanFactory> */
    use HasFactory, SoftDeletes, HasHistoryObserver;
    protected $fillable = [
        'name',
        'leader_id',
        'type',
        'description',
    ];

    public function leader():BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function requeriments(): HasMany
    {
        return $this->hasMany(ClanRequirement::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(GameAccount::class);
    }
}
