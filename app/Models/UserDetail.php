<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'onboarded',
        'terms_accepted',
        'privacy_accepted',
        'push_notifications',
        'active',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
