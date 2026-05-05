<?php

namespace App\Observers;

use App\Models\Coupon;
use App\Models\Notification;

class CouponObserver
{
    /**
     * Handle the Coupon "created" event.
     */
    public function created(Coupon $coupon): void
    {
        Notification::create([
            'type' => 'coupon_created',
            'title' => 'Thêm mã giảm giá mới',
            'message' => "Bạn vừa tạo mã giảm giá <strong class='text-brown-900'>{$coupon->code}</strong> (Giảm {$coupon->discount_percent}%).",
            'data' => ['coupon_id' => $coupon->id],
            'is_read' => false,
            'user_id' => null,
        ]);
    }

    /**
     * Handle the Coupon "updated" event.
     */
    public function updated(Coupon $coupon): void
    {
        Notification::create([
            'type' => 'coupon_updated',
            'title' => 'Cập nhật mã giảm giá',
            'message' => "Mã giảm giá <strong class='text-brown-900'>{$coupon->code}</strong> đã được cập nhật thông tin.",
            'data' => ['coupon_id' => $coupon->id],
            'is_read' => false,
            'user_id' => null,
        ]);
    }

    /**
     * Handle the Coupon "deleted" event.
     */
    public function deleted(Coupon $coupon): void
    {
        Notification::create([
            'type' => 'coupon_deleted',
            'title' => 'Xóa mã giảm giá',
            'message' => "Mã giảm giá <strong class='text-brown-900'>{$coupon->code}</strong> đã bị xóa khỏi hệ thống.",
            'data' => ['coupon_id' => $coupon->id],
            'is_read' => false,
            'user_id' => null,
        ]);
    }
}