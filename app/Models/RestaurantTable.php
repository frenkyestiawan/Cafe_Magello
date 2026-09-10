<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestaurantTable extends Model
{
    protected $fillable = [
        'table_number',
        'capacity',
        'is_available',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
