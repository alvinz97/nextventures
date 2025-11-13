<?php

namespace Domain\Order\Models;

use App\Models\User;
use Domain\Order\Factories\OrderFactory;
use Domain\Refund\Models\Refund;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'total',
        'status',
        'meta'
    ];

    protected $casts = [
        'meta' => 'array',
    ];


    protected static function newFactory(): OrderFactory
    {
        return new OrderFactory();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(OrderNotification::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }
}
