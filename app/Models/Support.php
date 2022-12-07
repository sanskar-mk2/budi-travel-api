<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic',
        'subject',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
