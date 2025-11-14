<?php

namespace Domain\Payment\Jobs;

use Domain\Order\Jobs\FinalizeOrderJob;
use Domain\Order\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SimulatePaymentJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(): void
    {
        sleep(2);

        $success = rand(1, 10) > 2;

        if ($success) {
            $this->order->update([
                'status' => 'payment_simulated'
            ]);
            dispatch(new FinalizeOrderJob($this->order, true));
        } else {
            dispatch(new FinalizeOrderJob($this->order, false));
        }
    }

}
