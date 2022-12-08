<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'support_id',
        'user_id',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
