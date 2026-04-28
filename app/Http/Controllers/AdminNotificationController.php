<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::latest()
            ->take(30)
            ->get();

        return view('admin.notifications', compact('notifications'));
    }
}