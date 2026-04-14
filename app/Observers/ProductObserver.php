<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Notification;

class ProductObserver
{
    public function created(Product $product): void
    {
        Notification::create([
            'type' => 'product_created',
            'title' => 'Sản phẩm mới',
            'message' => "Sản phẩm <strong>{$product->name}</strong> đã được thêm vào menu.",
            'data' => ['product_id' => $product->id],
        ]);
    }

    public function updated(Product $product): void
    {
        Notification::create([
            'type' => 'product_updated',
            'title' => 'Cập nhật sản phẩm',
            'message' => "Thông tin sản phẩm <strong>{$product->name}</strong> đã thay đổi.",
            'data' => ['product_id' => $product->id],
        ]);
    }

    public function deleted(Product $product): void
    {
        Notification::create([
            'type' => 'product_deleted',
            'title' => 'Xóa sản phẩm',
            'message' => "Sản phẩm <strong>{$product->name}</strong> đã bị xóa.",
            'data' => ['product_id' => $product->id],
        ]);
    }
}