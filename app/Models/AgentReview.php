<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentReview extends Model
{
    use HasFactory;

    public function agent()
    {
        return $this->belongsTo(\App\Models\User::class, 'agent_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function averate_rating()
    {
        return $this->agentReviews()->avg('rating');
    }
}
