<?php

namespace App\Models;

use App\Traits\HasHistoryObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClanRequirement extends Model
{
    /** @use HasFactory<\Database\Factories\ClanRequirementFactory> */
    use HasFactory, SoftDeletes, HasHistoryObserver;
    protected $fillable = [
        'clan_id',
        'label',
        'min_score',
        'priority',
        'description',
    ];

    public function clan(): BelongsTo
    {
        return $this->belongsTo(Clan::class);
    }
}
