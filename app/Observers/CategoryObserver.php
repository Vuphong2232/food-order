<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Notification;

class CategoryObserver
{
    public function created(Category $category): void
    {
        Notification::create([
            'type' => 'category_created',
            'title' => 'Thêm danh mục mới',
            'message' => "Bạn vừa tạo danh mục <strong class='text-brown-900'>{$category->name}</strong>.",
            'data' => [
                'category_id' => $category->id,
            ],
            'is_read' => false,
            'user_id' => auth()->id(),
        ]);
    }

    public function updated(Category $category): void
    {
        Notification::create([
            'type' => 'category_updated',
            'title' => 'Cập nhật danh mục',
            'message' => "Danh mục <strong class='text-brown-900'>{$category->name}</strong> đã được cập nhật thông tin.",
            'data' => [
                'category_id' => $category->id,
            ],
            'is_read' => false,
            'user_id' => auth()->id(),
        ]);
    }

    public function deleted(Category $category): void
    {
        Notification::create([
            'type' => 'category_deleted',
            'title' => 'Xóa danh mục',
            'message' => "Danh mục <strong class='text-brown-900'>{$category->name}</strong> đã bị xóa khỏi hệ thống.",
            'data' => [
                'category_id' => $category->id,
            ],
            'is_read' => false,
            'user_id' => auth()->id(),
        ]);
    }
}