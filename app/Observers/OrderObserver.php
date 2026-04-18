<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Notification;

class OrderObserver
{
    public function created(Order $order): void
    {
        // Admin notification
        Notification::create([
            'user_id' => null,
            'type' => 'order_success',
            'title' => 'Đơn hàng mới',
            'message' => "Khách hàng <strong>{$order->name}</strong> vừa đặt đơn <strong>{$order->code}</strong>.",
            'data' => [
                'order_id' => $order->id,
                'order_code' => $order->code,
                'amount' => $order->total_amount,
            ],
            'is_read' => 0,
        ]);

        // User notification
        if ($order->user_id) {
            Notification::create([
                'user_id' => $order->user_id,
                'type' => 'order_created_user',
                'title' => 'Đặt hàng thành công',
                'message' => "Bạn đã đặt hàng thành công. Đơn hàng <strong>{$order->code}</strong> đang chờ xử lý.",
                'data' => [
                    'order_id' => $order->id,
                    'order_code' => $order->code,
                    'amount' => $order->total_amount,
                ],
                'is_read' => 0,
            ]);
        }
    }
}