<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $appends = ['average_rating'];

    public function getAverageRatingAttribute()
    {
        return $this->agentReviews->avg('rating');
    }

    public function supports()
    {
        return $this->hasMany(\App\Models\Support::class);
    }

    public function routeNotificationForFcm()
    {
        return $this->device->device_id;
        // $user->notify(new FireBaseNotif);
    }

    public function device()
    {
        return $this->hasOne(\App\Models\Device::class);
    }

    public function profile()
    {
        return $this->hasOne(\App\Models\Profile::class);
    }

    public function coordinate()
    {
        return $this->hasOne(\App\Models\Coordinate::class);
    }

    public function userDetail()
    {
        return $this->hasOne(\App\Models\UserDetail::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function agentReviews()
    {
        return $this->hasMany(\App\Models\AgentReview::class, 'agent_id');
    }

    public function userReviews()
    {
        return $this->hasMany(\App\Models\AgentReview::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->profile()->create();
            $user->userDetail()->create();
            $user->coordinates()->create();
        });
    }

    public function offers()
    {
        return $this->hasMany(\App\Models\Offer::class, 'created_by');
    }

    public function agentStatus()
    {
        return $this->hasOne(\App\Models\AgentStatus::class);
    }

    public function projects()
    {
        return $this->hasMany(\App\Models\Project::class, 'user_id');
    }

    public function agentProjects()
    {
        // agent may have many projects through offers
        return $this->hasManyThrough(\App\Models\Project::class, \App\Models\Offer::class, 'created_by', 'offer_id');
    }

    public function misc()
    {
        return $this->hasOne(\App\Models\Misc::class);
    }
}
