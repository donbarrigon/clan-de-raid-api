<?php

namespace App\Models;

use App\Traits\HasHistoryObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailChange extends Model
{
    use HasFactory, HasHistoryObserver;
    
    protected $fillable = [
        'user_id',
        'new_email',
        'token',
        'expires_at',
    ];

    protected $dates = ['expires_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
