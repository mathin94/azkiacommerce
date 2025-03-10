<?php

namespace App\Services\Shop;

use App\Enums\OrderStatus;
use App\Models\Shop\Order;

class CompleteOrderService {
    public array $errors;

    public function __construct(
        private int $order_id
    ) {}

    public function execute(): bool
    {
        $order = Order::find($this->order_id);

        if (!$order) {
            $this->errors[] = 'Pesanan tidak ditemukan';
        }

        if (!is_null($order->canceled_at)) {
            $this->errors[] = 'Pesanan ini telah dibatalkan';
        }

        if ($order->is_completed) {
            $this->errors[] = 'Pesanan sudah diselesaikan sebelumnya';
        }

        if (!$order->trackable) {
            $this->errors[] = 'Pesanan tidak dapat diselesaikan, karena resi belum di input';
        }

        if (!empty($this->errors)) {
            return false;
        }

        $order->status = OrderStatus::Completed;
        $order->save();
        return true;
    }

    public function getErrorMessage(): ?string
    {
        if (empty($this->errors)) {
            return null;
        }

        return implode(', ', $this->errors);
    }
}
