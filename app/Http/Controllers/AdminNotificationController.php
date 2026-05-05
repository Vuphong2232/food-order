<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::whereNull('user_id')
            ->whereIn('type', [
                'order_success',

                'product_created',
                'product_updated',
                'product_deleted',

                'category_created',
                'category_updated',
                'category_deleted',

                'coupon_created',
                'coupon_updated',
                'coupon_deleted',

                'user_register',
                'revenue_threshold',
                'best_seller',
            ])
            ->latest()
            ->take(30)
            ->get();

        return view('admin.notifications', [
            'notifications' => $notifications,
            'isUserNotificationPage' => false,
        ]);
    }
}