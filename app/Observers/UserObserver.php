<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Notification;

class UserObserver
{
    public function created(User $user): void
    {
        Notification::create([
            'type' => 'user_register',
            'title' => 'Thành viên mới',
            'message' => "Người dùng <strong>{$user->username}</strong> vừa tham gia hệ thống.",
            'data' => ['user_id' => $user->id],
        ]);
    }
}