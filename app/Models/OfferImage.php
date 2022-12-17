<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'image',
    ];

    public function offer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Offer::class, 'offer_id');
    }

    public function getImageAttribute($value)
    {
        return $value ? asset('storage/'.$value) : null;
    }
}
