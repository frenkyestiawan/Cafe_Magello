<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'price',
        'category_id',
        'description',
        'image',
        'is_available',
    ];

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(MenuVariant::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getAvailableVariantsAttribute()
    {
        return $this->variants->where('is_available', true)->sortBy('price')->values();
    }

    public function getFormattedImageAttribute(): ?string
    {
        if (!empty($this->image_url)) {
            return $this->image_url;
        }
        if (!empty($this->image)) {
            return asset('storage/' . $this->image);
        }
        return null;
    }
}
