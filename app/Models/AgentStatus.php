<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'approved',
        'suspended',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getApprovedAttribute($value)
    {
        return (bool) $value;
    }

    public function getSuspendedAttribute($value)
    {
        return (bool) $value;
    }
}
