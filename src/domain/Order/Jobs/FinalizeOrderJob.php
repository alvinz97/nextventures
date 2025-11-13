<?php

namespace Domain\Order\Jobs;


use Domain\Order\Models\Order;
use Domain\Order\Models\OrderNotification;
use Domain\Order\Services\KpiService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

class FinalizeOrderJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public Order $order;
    public bool $success;

    public function __construct(Order $order, bool $success)
    {
        $this->order = $order;
        $this->success = $success;
    }

    public function handle()
    {
        if ($this->success) {
            $this->order->update([
                'status' => 'completed'
            ]);
            KpiService::updateKpis($this->order);
            OrderNotification::create([
                'order_id' => $this->order->id,
                'user_id' => $this->order->user_id,
                'status' => 'success',
                'total' => $this->order->total,
                'message' => 'Order processed successfully.',
            ]);
        } else {
            $this->order->update([
                'status' => 'failed'
            ]);
            OrderNotification::create([
                'order_id' => $this->order->id,
                'user_id' => $this->order->user_id,
                'status' => 'failed',
                'total' => $this->order->total,
                'message' => 'Order payment failed.',
            ]);
        }
    }
}
