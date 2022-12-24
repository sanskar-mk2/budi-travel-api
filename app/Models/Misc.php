<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Misc extends Model
{
    use HasFactory;

    protected $fillabe = [
        'user_id',
        'address',
        'tagline',
    ];

    protected $appends = [
        'total_projects',
        'total_offers',
        'total_completed',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function getTotalProjectsAttribute(): int
    {
        return $this->user->projects->count();
    }

    public function getTotalOffersAttribute(): int
    {
        return $this->user->offers->count();
    }

    public function getTotalCompletedAttribute(): int
    {
        return $this->user->projects->where('agent_finished_at', '!=', null)->where('user_finished_at', '!=', null)->count();
    }
}
