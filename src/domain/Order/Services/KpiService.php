<?php

namespace Domain\Order\Services;

use Domain\Order\Models\Order;
use Illuminate\Support\Facades\Redis;

class KpiService
{
    public static function updateKpis(Order $order): void
    {
        $date = now()->toDateString();
        Redis::hincrbyfloat("kpi:$date", 'revenue', $order->total);
        Redis::hincrby("kpi:$date", 'order_count', 1);

        $revenue = Redis::hget("kpi:$date", 'revenue');
        $count = Redis::hget("kpi:$date", 'order_count');
        $avg = $count > 0 ? $revenue / $count : 0;

        Redis::hset("kpi:$date", 'avg_order_value', $avg);
        Redis::zincrby("leaderboard", $order->total, $order->user_id);
    }

    public static function updateRefunds(Order $order, float $amount): void
    {
        $date = now()->toDateString();
        Redis::hincrbyfloat("kpi:$date", 'revenue', -$amount);
        Redis::zincrby("leaderboard", -$amount, $order->user_id);
    }
}
