<?php

namespace Domain\Order\Jobs;

use Domain\Order\Models\Order;
use Domain\Order\Models\OrderItem;
use Domain\Payment\Jobs\SimulatePaymentJob;
use Domain\Product\Models\Product;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessOrderJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public array $record;

    public function __construct($record)
    {
        $this->record = $record;
    }

    public function handle(): void
    {
        DB::transaction(function () {
            $order = Order::create([
                'order_number' => $this->record['order_number'],
                'user_id'  => (int)$this->record['user_id'],
                'total'        => 0,
                'status'       => 'pending',
            ]);

            $items = json_decode($this->record['items'], true);
            $total = 0;

            foreach ($items as $item) {
                $product = Product::lockForUpdate()->where('slug', $item['product_slug'])->first();
                if (!$product || $product->stock < $item['quantity']) {
                    throw new Exception("Insufficient stock for product {$product->name}");
                }

                $product->decrement('stock', $item['quantity']);

                $itemTotal = $item['quantity'] * $product->price;
                $total += $itemTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $itemTotal,
                ]);
            }

            $order->update(['total' => $total, 'status' => 'stock_reserved']);

            dispatch(new SimulatePaymentJob($order));
        });
    }

    public function failed(Throwable $e): void
    {
        Log::error("Order failed: " . $e->getMessage());
    }
}
