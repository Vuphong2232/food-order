<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class UserNotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->whereIn('type', [
                'order_created_user',
                'order_process_updated',
                'order_completed',
                'order_cancelled',
                'payment_success',
            ])
            ->latest()
            ->take(30)
            ->get();

        return view('admin.notifications', [
            'notifications' => $notifications,
            'isUserNotificationPage' => true,
        ]);
    }
}