<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CheckSystemStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // 1. KIỂM TRA DOANH THU VƯỢT NGƯỠNG (Ví dụ: 10 triệu/ngày)
        $todayRevenue = Order::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_amount');

        if ($todayRevenue > 10000000) { // Ngưỡng 10 triệu
            // Kiểm tra xem đã thông báo hôm nay chưa (tránh lặp)
            $existing = Notification::where('type', 'revenue_threshold')
                ->whereDate('created_at', today())
                ->first();

            if (!$existing) {
                Notification::create([
                    'type' => 'revenue_threshold',
                    'title' => 'Chúc mừng! Doanh thu kỷ lục',
                    'message' => "Doanh thu hôm nay đã đạt <strong>" . number_format($todayRevenue) . "₫</strong>. Vượt ngưỡng mục tiêu!",
                    'data' => ['revenue' => $todayRevenue]
                ]);
            }
        }

        // 2. KIỂM TRA SẢN PHẨM BÁN CHẠY MỚI
        // Logic: Tìm sản phẩm có tổng số lượng bán tăng vọt so với ngày trước
        // Hoặc đơn giản là: Sản phẩm nào vừa đạt mốc 100 đơn
        
        $topProduct = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'completed')
            ->groupBy('product_id')
            ->having('total_sold', '>', 100) // Ví dụ: Mốc 100 đơn
            ->orderByDesc('total_sold')
            ->first();

        if ($topProduct && $topProduct->product) {
            $product = $topProduct->product;
            
            // Kiểm tra xem đã thông báo sản phẩm này chưa
            $existing = Notification::where('type', 'best_seller')
                ->where('data->product_id', $product->id)
                ->first();

            if (!$existing) {
                Notification::create([
                    'type' => 'best_seller',
                    'title' => 'Sản phẩm Best Seller',
                    'message' => "Chúc mừng <strong>{$product->name}</strong> chính thức trở thành sản phẩm bán chạy nhất (đã bán > 100).",
                    'data' => ['product_id' => $product->id, 'sold' => $topProduct->total_sold]
                ]);
            }
        }
    }
}