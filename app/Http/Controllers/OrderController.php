<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\CartItem; 
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 

class OrderController extends Controller
{
    /**
     * Hiển thị form đặt hàng (trang hiện tại của bạn)
     */
    public function index()
    {
        return view('checkout');
    }

    /**
     * Xử lý đặt hàng
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|max:255',
        'address' => 'required|string|max:500',
        'note' => 'nullable|string|max:1000',
        'payment_method' => 'required|in:cod,bank,vnpay',
    ], [
        'name.required' => 'Vui lòng nhập họ tên',
        'phone.required' => 'Vui lòng nhập số điện thoại',
        'email.required' => 'Vui lòng nhập email',
        'email.email' => 'Email không đúng định dạng',
        'address.required' => 'Vui lòng nhập địa chỉ nhận hàng',
        'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
    ]);

    $cartItems = CartItem::with('product')
        ->where('user_id', Auth::id())
        ->get();

    if ($cartItems->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Giỏ hàng trống, không thể đặt hàng.',
        ], 400);
    }

    $totalAmount = 0;
    foreach ($cartItems as $item) {
        if ($item->product) {
            $totalAmount += $item->product->price * $item->quantity;
        }
    }

    $orderCode = 'DH-' . strtoupper(uniqid());

    DB::beginTransaction();

    try {
        $order = Order::create([
            'code' => $orderCode,
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'note' => $validated['note'] ?? null,
            'payment_method' => $validated['payment_method'],
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'process_status' => 'received',
        ]);



        foreach ($cartItems as $item) {
            if ($item->product) {
                OrderItem::create([
                    'product_id' => $item->product->id,
                    'order_id' => $order->id,
                     'name'     => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }
        }

        CartItem::where('user_id', Auth::id())->delete();

        session()->flash('order_code', $orderCode);
        session()->flash('order_date', now()->format('d/m/Y - H:i'));

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Đặt hàng thành công!',
            'redirect' => route('thankyou'),
        ]);
    } catch (\Throwable $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}

public function adminNotifications()
{
    $notifications = Notification::whereNull('user_id')
        ->latest()
        ->get();

    return view('admin.notifications', compact('notifications'));
}

// API để lấy số lượng thông báo mới cho nút chuông
public function getNotificationCount()
{
    $count = \App\Models\Notification::where('is_read', false)->count();
    return response()->json(['count' => $count]);
}

public function userNotifications()
{
    $notifications = Notification::where('user_id', auth()->id())
        ->latest()
        ->get();

    return view('admin.notifications', compact('notifications'));
}

    /**
     * Trang cảm ơn
     */
    public function thankyou()
    {
        $orderCode = session('order_code');
        $orderDate = session('order_date');

        // Nếu không có mã đơn → về trang chủ (tránh truy cập trực tiếp)
        if (!$orderCode) {
            return redirect()->route('home');
        }

        return view('thankyou', compact('orderCode', 'orderDate'));
    }

public function history(Request $request)
{
    $isAdminMode = session('admin_mode', false)
        && auth()->check()
        && auth()->user()->role === 'admin';

    $query = \App\Models\Order::with(['user', 'items.product']);

    if (!$isAdminMode) {
        $query->where('user_id', auth()->id());
    }

    if ($request->search) {
        $query->where('code', 'like', '%' . $request->search . '%');
    }

    if ($request->filter === 'processing') {
        $query->where('process_status', '!=', 'completed');
    }

    $orders = $query->orderBy('created_at', 'desc')
        ->paginate(10)
        ->appends($request->all());

    return view('orders.history', compact('orders', 'isAdminMode'));
}

public function getDetailApi($id)
{
    $order = \App\Models\Order::with(['user', 'items.product'])->findOrFail($id);

    $isAdminMode = session('admin_mode', false)
        && auth()->check()
        && auth()->user()->role === 'admin';

    if (!$isAdminMode && $order->user_id !== auth()->id()) {
        return response()->json([
            'success' => false,
            'message' => 'Không có quyền truy cập'
        ], 403);
    }

    $items = [];

    foreach ($order->items as $item) {
        $price = $item->price ?? $item->unit_price ?? 0;

        $items[] = [
            'name' => $item->product->name ?? 'Sản phẩm',
            'quantity' => $item->quantity,
            'price' => $price,
            'subtotal' => $price * $item->quantity,
        ];
    }

    return response()->json([
        'success' => true,
        'order' => [
            'id' => $order->id,
            'code' => $order->code,
            'status' => $order->status,
            'process_status' => $order->process_status ?? 'received',
            'created_at' => $order->created_at->toDateTimeString(),
            'name' => $order->name ?? $order->customer_name ?? $order->receiver_name ?? '',
            'phone' => $order->phone ?? $order->customer_phone ?? '',
            'address' => $order->address ?? '',
            'buyer_email' => $order->user->email ?? '',
            'total_amount' => $order->total_amount,
            'items' => $items,
        ]
    ]);
}


public function updateProcess(Request $request, $id)
{
    $isAdminMode = session('admin_mode', false)
        && auth()->check()
        && auth()->user()->role === 'admin';

    if (!$isAdminMode) {
        return response()->json([
            'success' => false,
            'message' => 'Bạn không có quyền cập nhật đơn hàng'
        ], 403);
    }

    $request->validate([
        'process_status' => 'required|in:received,preparing,shipping,completed',
    ]);

    $order = \App\Models\Order::findOrFail($id);

    // Lưu trạng thái cũ (nếu cần so sánh)
    $oldStatus = $order->process_status;

    $order->process_status = $request->process_status;
    $order->status = $request->process_status === 'completed' ? 'completed' : 'pending';
    $order->save();

    // 🔔 Tạo notification cho USER
    if ($order->user_id) {

        $title = 'Cập nhật đơn hàng';
        $message = '';

        switch ($order->process_status) {
            case 'received':
                $message = "Đơn hàng <strong>{$order->code}</strong> đã được tiếp nhận.";
                break;

            case 'preparing':
                $message = "Đơn hàng <strong>{$order->code}</strong> đang được chuẩn bị.";
                break;

            case 'shipping':
                $message = "Đơn hàng <strong>{$order->code}</strong> đang được giao, hãy chú ý điện thoại nhé.";
                break;

            case 'completed':
                $message = "
                Đơn hàng <strong>{$order->code}</strong> đã hoàn tất 🎉<br>
                Cảm ơn bạn đã ủng hộ ❤️<br><br>

                ⭐ Nếu bạn hài lòng, hãy đánh giá 5 sao để ủng hộ shop nhé!<br>
                💬 Nếu có góp ý, đừng ngại để lại bình luận để shop cải thiện tốt hơn.<br><br>

                <b>Chúc bạn ăn ngon miệng!</b>
                ";
        }

        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'order_process_updated',
            'title' => $title,
            'message' => $message,
            'data' => [
                'order_id' => $order->id,
                'order_code' => $order->code,
                'process_status' => $order->process_status,
            ],
            'is_read' => 0,
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Cập nhật trạng thái đơn hàng thành công'
    ]);
}

public function destroy($id)
{
    $isAdminMode = session('admin_mode', false)
        && auth()->check()
        && auth()->user()->role === 'admin';

    if (!$isAdminMode) {
        return response()->json([
            'success' => false,
            'message' => 'Bạn không có quyền xóa đơn hàng'
        ], 403);
    }

    $order = \App\Models\Order::findOrFail($id);
    $order->delete();

    return response()->json([
        'success' => true,
        'message' => 'Xóa đơn hàng thành công'
    ]);
}

public function track($id)
{
    $order = \App\Models\Order::with(['user', 'items.product'])->findOrFail($id);

    $isAdminMode = session('admin_mode', false)
        && auth()->check()
        && auth()->user()->role === 'admin';

    if (!$isAdminMode && $order->user_id !== auth()->id()) {
        abort(403);
    }

    if (request()->ajax()) {
        return view('orders.track', compact('order'));
    }

    return view('orders.track_page', compact('order'));
}


public function adminReport(Request $request)
{
    if (auth()->check() && auth()->user()->role !== 'admin') {
        abort(403, 'Bạn không có quyền truy cập trang này.');
    }

    $period = $request->get('period', 'month');

    $completedOrderQuery = Order::where('process_status', 'completed');

    $completedOrderQuery->when($period == 'week', function ($q) {
        return $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    })->when($period == 'month', function ($q) {
        return $q->whereMonth('created_at', now()->month)
                 ->whereYear('created_at', now()->year);
    })->when($period == 'year', function ($q) {
        return $q->whereYear('created_at', now()->year);
    });

    $processingOrderQuery = Order::where('process_status', '!=', 'completed');

    $processingOrderQuery->when($period == 'week', function ($q) {
        return $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    })->when($period == 'month', function ($q) {
        return $q->whereMonth('created_at', now()->month)
                 ->whereYear('created_at', now()->year);
    })->when($period == 'year', function ($q) {
        return $q->whereYear('created_at', now()->year);
    });

    $revenueQuery = Order::where('process_status', 'completed');

    $revenueQuery->when($period == 'week', function ($q) {
        return $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    })->when($period == 'month', function ($q) {
        return $q->whereMonth('created_at', now()->month)
                 ->whereYear('created_at', now()->year);
    })->when($period == 'year', function ($q) {
        return $q->whereYear('created_at', now()->year);
    });

    $revenue = (clone $revenueQuery)->sum('total_amount');
    $ordersCount = (clone $completedOrderQuery)->count();
    $processingOrders = (clone $processingOrderQuery)->count();

    $topProductsQuery = OrderItem::select(
            'products.id',
            'products.name',
            'products.image',
            'products.price',
            DB::raw('SUM(order_items.quantity) as sold_count')
        )
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('orders.process_status', 'completed');

    $topProductsQuery->when($period == 'week', function ($q) {
        return $q->whereBetween('orders.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    })->when($period == 'month', function ($q) {
        return $q->whereMonth('orders.created_at', now()->month)
                 ->whereYear('orders.created_at', now()->year);
    })->when($period == 'year', function ($q) {
        return $q->whereYear('orders.created_at', now()->year);
    });

    $topProducts = $topProductsQuery
        ->groupBy('products.id', 'products.name', 'products.image', 'products.price')
        ->orderByDesc('sold_count')
        ->limit(3)
        ->get()
        ->map(function ($p) {
            if (!$p->image) {
                $p->image_url = 'https://via.placeholder.com/400';
            } elseif (str_starts_with($p->image, 'http://') || str_starts_with($p->image, 'https://')) {
                $p->image_url = $p->image;
            } else {
                $p->image_url = asset('storage/' . ltrim($p->image, '/'));
            }

            return $p;
        });

    $newUsersQuery = User::query();

    $newUsersQuery->when($period == 'week', function ($q) {
        return $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    })->when($period == 'month', function ($q) {
        return $q->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
    })->when($period == 'year', function ($q) {
        return $q->whereYear('created_at', now()->year);
    });

    $newUsers = $newUsersQuery->count();
    $avgOrderValue = $ordersCount > 0 ? $revenue / $ordersCount : 0;
    $bestSellerIds = $topProducts->pluck('id')->toArray();

    $reportStats = [
        'revenue' => $revenue,
        'orders_count' => $ordersCount,
        'products_sold' => $topProducts->sum('sold_count'),
        'processing_orders' => $processingOrders,
        'avg_order_value' => $avgOrderValue,
        'new_users' => $newUsers,
    ];

    return view('report-page', compact('reportStats', 'topProducts', 'bestSellerIds'));
}
}