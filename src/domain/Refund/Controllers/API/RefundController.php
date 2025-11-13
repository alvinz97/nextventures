<?php

namespace Domain\Refund\Controllers\API;

use App\Http\Controllers\Controller;
use Domain\Order\Models\Order;
use Domain\Refund\Jobs\ProcessRefundJob;
use Domain\Refund\Models\Refund;
use Domain\Refund\Requests\RefundRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RefundController extends Controller
{
    public function store(RefundRequest $refundRequest): JsonResponse
    {
        $data = $refundRequest->data();

        $order = Order::findOrFail($data->order);

        if ($data->amount > $order->total) {
            return response()->json(['error' => 'Refund amount exceeds order total'], 400);
        }

        $refund = Refund::create([
            'order_id' => $order->id,
            'refund_reference' => Str::uuid(),
            'amount' => $data->amount,
            'status' => 'pending',
        ]);

        ProcessRefundJob::dispatch($refund->id);

        return response()->json([
            'message' => 'Refund request queued successfully',
            'refund_reference' => $refund->refund_reference
        ]);
    }
}
