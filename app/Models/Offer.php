<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    public function getThumbnailAttribute($value)
    {
        return $value ? asset('storage/'.$value) : null;
    }

    public function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => floatval($value / 100),
            set: fn ($value) => $value * 100,
        );
    }

    public function createdBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function offerImages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\OfferImage::class, 'offer_id');
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }
}
