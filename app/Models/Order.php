<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;
    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_DIPROSES = 'diproses';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_SUDAH_DIAMBI = 'sudah_diambil';

    protected $fillable = [
        'restaurant_table_id',
        'order_code',
        'customer_name',
        'customer_phone',
        'status',
        'total_amount',
        'payment_status',
        'user_id',
    ];

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_MENUNGGU => 'Menunggu',
            self::STATUS_DIPROSES => 'Diproses',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_SUDAH_DIAMBI => 'Sudah Diambil',
            default => ucfirst((string) $this->status),
        };
    }

    public function canTransitionTo(string $nextStatus): bool
    {
        $transitions = [
            self::STATUS_MENUNGGU => self::STATUS_DIPROSES,
            self::STATUS_DIPROSES => self::STATUS_SELESAI,
            self::STATUS_SELESAI => self::STATUS_SUDAH_DIAMBI,
        ];

        return ($transitions[$this->status] ?? null) === $nextStatus;
    }

    protected static function newFactory()
    {
        return OrderFactory::new();
    }

    public function restaurantTable(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
