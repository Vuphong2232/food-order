<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Notification;

class OrderObserver
{
    public function created(Order $order): void
    {
        Notification::create([
            'type' => 'order_success',
            'title' => 'Đơn hàng mới',
            'message' => "Khách hàng <strong>{$order->name}</strong> vừa đặt đơn <strong>{$order->code}</strong>.",
            'data' => [
                'order_id' => $order->id,
                'order_code' => $order->code,
                'amount' => $order->total_amount,
            ],
        ]);
    }
}