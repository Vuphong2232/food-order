<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function createReviewSp($orderId, $productId)
{
    $order = Order::with('items.product')
        ->where('id', $orderId)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    if ($order->status !== 'completed' && $order->process_status !== 'completed') {
        return response()->make('Chỉ được đánh giá khi đơn hàng đã hoàn tất', 403);
    }

    $item = $order->items->firstWhere('product_id', $productId);

    if (!$item) {
        abort(404);
    }

    $product = $item->product;

    $reviewedProductIds = Review::where('user_id', auth()->id())
        ->where('order_id', $order->id)
        ->pluck('product_id')
        ->toArray();

    return view('orders.review_sp', compact('order', 'product', 'item', 'reviewedProductIds'));
}

    public function createReview($orderId, $productId)
    {
        $order = Order::with('items.product')
            ->where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($order->status !== 'completed' && $order->process_status !== 'completed') {
            return response()->make('Chỉ được đánh giá khi đơn hàng đã hoàn tất', 403);
        }

        $item = $order->items->firstWhere('product_id', $productId);

        if (!$item) {
            abort(404);
        }

        $product = $item->product;

        return view('orders.review', compact('order', 'product', 'item'));
    }

    public function store(Request $request)
    {
        try {
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập'
                ], 401);
            }

            $validated = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'product_id' => 'required|exists:products,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
            ], [
                'order_id.required' => 'Thiếu mã đơn hàng',
                'product_id.required' => 'Thiếu sản phẩm cần đánh giá',
                'rating.required' => 'Vui lòng chọn số sao',
            ]);

            $order = Order::with('items')
                ->where('id', $validated['order_id'])
                ->where('user_id', auth()->id())
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng không tồn tại hoặc không thuộc về bạn'
                ], 403);
            }

            if ($order->status !== 'completed' && $order->process_status !== 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ được đánh giá khi đơn hàng đã hoàn tất'
                ], 400);
            }

            $hasProductInOrder = $order->items->contains(function ($item) use ($validated) {
                return (int) $item->product_id === (int) $validated['product_id'];
            });

            if (!$hasProductInOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm này không có trong đơn hàng'
                ], 400);
            }

            $existingReview = Review::where('user_id', auth()->id())
                ->where('order_id', $validated['order_id'])
                ->where('product_id', $validated['product_id'])
                ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false
                ], 400);
            }

            $review = Review::create([
                'user_id' => auth()->id(),
                'order_id' => $validated['order_id'],
                'product_id' => $validated['product_id'],
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đánh giá thành công',
                'review' => $review
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}