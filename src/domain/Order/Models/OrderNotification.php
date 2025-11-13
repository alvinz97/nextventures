<?php

namespace Domain\Order\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderNotification extends Model
{
    use SoftDeletes;

    protected $table = 'order_notifications';

    protected $fillable = [
        'order_id',
        'customer_id',
        'status',
        'total',
        'channel',
        'payload',
        'message',
    ];

    protected $casts = [
        'payload' => 'array'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
