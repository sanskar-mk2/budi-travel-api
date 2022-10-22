<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_picture',
        'instagram_handle',
        'tiktok_handle',
        'facebook_handle',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getProfilePictureAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}
