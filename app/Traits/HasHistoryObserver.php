<?php

namespace App\Traits;

use App\Models\History;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

trait HasHistoryObserver
{
    public static function bootHasHistoryObserver(): void
    {
        static::created(function ($model) {
            self::logHistory($model, 'created', $model->getAttributes());
        });

        static::updated(function ($model) {
            self::logHistory($model, 'updated', $model->getChanges());
        });

        static::deleted(function ($model) {
            self::logHistory($model, 'deleted', $model->getAttributes());
        });

        static::restored(function ($model) {
            self::logHistory($model, 'restored', $model->getAttributes());
        });

        static::forceDeleted(function ($model) {
            self::logHistory($model, 'force_deleted', $model->getAttributes());
        });
    }

    protected static function logHistory($model, string $action, array $changes): void
    {
        History::create([
            'user_id'    => Auth::id(),
            'model'      => get_class($model),
            'model_id'   => $model->getKey(),
            'action'     => $action,
            'changes'    => self::filterAttributes($changes, $model),
        ]);
    }

    protected static function filterAttributes(array $attributes, $model): array
    {
        $excluded = property_exists($model, 'historyIgnore') ? $model->historyIgnore : ['created_at', 'updated_at', 'deleted_at'];
        return collect($attributes)
            ->except($excluded)
            ->toArray();
    }
    
    /**
     * Relación para obtener el historial de este modelo.
     */
    public function history(): HasMany
    {
        return $this->hasMany(History::class, 'model_id')
            ->where('model', get_class($this))
            ->latest();
    }
}
