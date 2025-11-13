<?php

namespace Domain\Refund\Data;

class RefundData
{
    public function __construct(
        public mixed $id,
        public int $user,
        public string $order,
        public float $amount,
    ) {
    }
}
