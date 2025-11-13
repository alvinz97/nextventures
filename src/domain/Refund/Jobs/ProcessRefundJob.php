<?php

namespace Domain\Refund\Jobs;


use Domain\Order\Services\KpiService;
use Domain\Refund\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessRefundJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $refundId;

    public function __construct(int $refundId)
    {
        $this->refundId = $refundId;
    }

    public function handle(): void
    {
        DB::transaction(function () {
            $refund = Refund::lockForUpdate()->find($this->refundId);

            if (!$refund || $refund->status === 'completed') {
                return;
            }

            $refund->update([
                'status' => 'processing'
            ]);

            $order = $refund->order;
            $order->decrement('total', $refund->amount);

            KpiService::updateRefunds($order, $refund->amount);

            $refund->update([
                'status' => 'completed'
            ]);
        });
    }

    public function failed(Throwable $e): void
    {
        Log::error("Refund Job failed: " . $e->getMessage());
        Refund::find($this->refundId)?->update([
            'status' => 'failed'
        ]);
    }
}
