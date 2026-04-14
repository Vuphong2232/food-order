<?php

namespace App\Http\Controllers;

use App\Models\Order;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->take(20)->get();

        return view('admin.notifications', compact('orders'));
    }
}