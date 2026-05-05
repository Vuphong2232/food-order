<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\CartItem; 
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 

class OrderController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with('product.reviews.user')
            ->where('user_id', Auth::id())
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return ($item->product->price ?? 0) * $item->quantity;
        });

        return view('partials.checkout', compact('cartItems', 'subtotal'));
    }

    public function checkCoupon(Request $request)
{
    $request->validate([
        'code' => 'required|string'
    ]);

    $cartItems = CartItem::with('product')
        ->where('user_id', Auth::id())
        ->get();

    $subtotal = $cartItems->sum(function ($item) {
        return ($item->product->price ?? 0) * $item->quantity;
    });

    $coupon = Coupon::whereRaw('UPPER(code) = ?', [strtoupper($request->code)])
        ->where('is_active', 1)
        ->first();

    if (!$coupon) {
        return response()->json([
            'success' => false,
            'message' => 'Mã giảm giá không hợp lệ',
        ]);
    }

    $discountAmount = $subtotal * ($coupon->discount_percent / 100);
    $total = $subtotal - $discountAmount;

    return response()->json([
        'success' => true,
        'code' => $coupon->code,
        'discount_percent' => $coupon->discount_percent,
        'subtotal' => $subtotal,
        'discount_amount' => $discountAmount,
        'total' => $total,
    ]);
}

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

    // --- BẮT ĐẦU SỬA: TÍNH TOÁN $subtotal ---
    $subtotal = 0;
    foreach ($cartItems as $item) {
        if ($item->product) {
            $subtotal += $item->product->price * $item->quantity;
        }
    }
    // --- KẾT THÚC SỬA ---

    $orderCode = 'DH-' . strtoupper(uniqid());

    // --- SỬA LOGIC GIẢM GIÁ ---
    $couponCode = strtoupper(trim($request->coupon_code ?? ''));
    $discountPercent = 0;
    $discountAmount = 0;

    if ($couponCode !== '') {
        $coupon = Coupon::whereRaw('UPPER(code) = ?', [$couponCode])
            ->where('is_active', 1)
            ->first();

        if ($coupon) {
            $discountPercent = $coupon->discount_percent;
            $discountAmount = $subtotal * ($discountPercent / 100);
        }
    }

    $totalAmount = $subtotal - $discountAmount;
    // --------------------------

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
            'payment_status' => $validated['payment_method'] === 'cod' ? 'unpaid' : 'unpaid',
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_status' => $validated['payment_method'] === 'bank' ? 'unpaid' : 'unpaid',
            'process_status' => $validated['payment_method'] === 'bank'
                ? 'waiting_payment'
                : 'received',
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

        DB::commit();

        if ($order->payment_method === 'bank') {
            return response()->json([
                'success' => true,
                'message' => 'Vui lòng quét mã QR để thanh toán.',
                'redirect' => route('bank.payment', $order->id),
            ]);
        }

        session()->flash('order_code', $orderCode);
        session()->flash('order_date', now()->format('d/m/Y - H:i'));

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

public function bankPayment(Order $order)
{
    if ($order->user_id !== Auth::id()) {
        abort(403);
    }

    if ($order->payment_method !== 'bank') {
        return redirect()->route('thankyou');
    }

    return view('payments.bank_qr', compact('order'));
}

public function confirmBankPayment(Order $order)
{
    if ($order->user_id !== Auth::id()) {
        abort(403);
    }

    if ($order->payment_method !== 'bank') {
        abort(400);
    }

    $order->update([
        'payment_status' => 'paid',
        'paid_at' => now(),
        'transaction_code' => 'BANK-DEMO-' . now()->format('YmdHis'),
        'process_status' => 'received',
        'status' => 'pending',
    ]);

    session()->flash('order_code', $order->code);
    session()->flash('order_date', $order->created_at->format('d/m/Y - H:i'));

    return redirect()->route('thankyou');
}
public function adminNotifications()
{
    $notifications = Notification::latest()->get();

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
        ->whereIn('type', [
            'order_process_updated',
            'order_status_updated',
            'order_completed',
            'order_cancelled',
            'payment_success',
        ])
        ->latest()
        ->get();

    return view('user.notifications', compact('notifications'));
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
            'name' => $item->name ?? $item->product->name ?? 'Sản phẩm',
            'quantity' => $item->quantity,
            'price' => $price,
            'subtotal' => $price * $item->quantity,
        ];
    }

    $paymentStatus = $order->payment_status ?? 'unpaid';
    $isPaid = $paymentStatus === 'paid';

  return response()->json([
        'success' => true,
        'order' => [
            'id' => $order->id,
            'code' => $order->code,
            'status' => $order->status,
            'process_status' => $order->process_status ?? 'received',
            'created_at' => $order->created_at->toDateTimeString(),

            'name' => $order->name ?? '',
            'phone' => $order->phone ?? '',
            'address' => $order->address ?? '',
            'buyer_email' => $order->user->email ?? '',

            'payment_method' => $order->payment_method ?? 'cod',
            'payment_status' => $order->payment_status ?? 'unpaid',
            'is_paid' => ($order->payment_status ?? 'unpaid') === 'paid',

            'original_total_amount' => $order->total_amount,
            'total_amount' => (($order->payment_status ?? 'unpaid') === 'paid') ? 0 : $order->total_amount,

            'items' => $items,
        ]
    ]);

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

//Trạng thái đơn hàng//
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
        'process_status' => 'required|in:waiting_payment,received,preparing,shipping,completed,cancelled',
    ]);

    $order = \App\Models\Order::findOrFail($id);

    // Lưu trạng thái cũ 
    $oldStatus = $order->process_status;

    $order->process_status = $request->process_status;

    if ($request->process_status === 'cancelled') {
        $order->status = 'cancelled';
    } elseif ($request->process_status === 'completed') {
        $order->status = 'completed';
    } else {
        $order->status = 'pending';
    }

    $order->save();

    // Thông báo cho người dùng khi admin cập nhật trạng thái đơn hàng
if (!empty($order->user_id)) {
    $title = 'Cập nhật đơn hàng';

    switch ($order->process_status) {
        case 'waiting_payment':
            $message = "Đơn hàng <strong>{$order->code}</strong> đang chờ thanh toán.";
            $type = 'order_process_updated';
            break;

        case 'received':
            $message = "Đơn hàng <strong>{$order->code}</strong> đã được tiếp nhận.";
            $type = 'order_process_updated';
            break;

        case 'preparing':
            $message = "Đơn hàng <strong>{$order->code}</strong> đang được chuẩn bị.";
            $type = 'order_process_updated';
            break;

        case 'shipping':
            $message = "Đơn hàng <strong>{$order->code}</strong> đang được giao, hãy chú ý điện thoại nhé.";
            $type = 'order_process_updated';
            break;

        case 'completed':
            $title = 'Cảm ơn bạn đã mua hàng';
            $message = "
                Đơn hàng <strong>{$order->code}</strong> đã hoàn tất 🎉<br>
                Cảm ơn bạn đã ủng hộ Món Ngon ❤️<br><br>
                ⭐ Nếu bạn hài lòng, hãy đánh giá 5 sao để ủng hộ shop nhé!<br>
                💬 Nếu có góp ý, đừng ngại để lại bình luận để shop cải thiện tốt hơn.<br><br>
                <b>Chúc bạn ăn ngon miệng!</b>
            ";
            $type = 'order_completed';
            break;

        case 'cancelled':
            $title = 'Đơn hàng đã bị hủy';
            $message = "Đơn hàng <strong>{$order->code}</strong> của bạn đã bị hủy, vui lòng kiểm tra lại hoặc đặt đơn hàng mới.";
            $type = 'order_cancelled';
            break;

        default:
            $message = "Đơn hàng <strong>{$order->code}</strong> vừa được cập nhật trạng thái.";
            $type = 'order_process_updated';
            break;
    }

    Notification::create([
        'user_id' => $order->user_id,
        'type' => $type,
        'title' => $title,
        'message' => $message,
        'data' => [
            'order_id' => $order->id,
            'order_code' => $order->code,
            'old_process_status' => $oldStatus,
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

    $period = $request->get('period', 'week');

    $now = now();

    if ($period === 'week') {
        // Từ thứ 2 đến chủ nhật
        $startDate = now()->startOfWeek(\Carbon\Carbon::MONDAY)->startOfDay();
        $endDate = now()->endOfWeek(\Carbon\Carbon::SUNDAY)->endOfDay();
    } elseif ($period === 'month') {
        // Từ ngày 1 đến ngày cuối tháng
        $startDate = now()->startOfMonth()->startOfDay();
        $endDate = now()->endOfMonth()->endOfDay();
    } elseif ($period === 'year') {
        // Từ 01/01 đến 31/12
        $startDate = now()->startOfYear()->startOfDay();
        $endDate = now()->endOfYear()->endOfDay();
    } else {
        // Mặc định nếu sai period
        $period = 'week';
        $startDate = now()->startOfWeek(\Carbon\Carbon::MONDAY)->startOfDay();
        $endDate = now()->endOfWeek(\Carbon\Carbon::SUNDAY)->endOfDay();
    }

    $completedOrderQuery = Order::whereBetween('created_at', [$startDate, $endDate])
        ->where(function ($q) {
            $q->where('status', 'completed')
              ->orWhere('process_status', 'completed');
        });

    $revenue = (clone $completedOrderQuery)->sum('total_amount');

    $ordersCount = (clone $completedOrderQuery)->count();

    $processingOrders = Order::whereBetween('created_at', [$startDate, $endDate])
    ->whereNotIn('status', ['completed', 'cancelled'])
    ->whereNotIn('process_status', ['completed', 'cancelled'])
    ->count();

    $productsSold = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
        ->whereBetween('orders.created_at', [$startDate, $endDate])
        ->where(function ($q) {
            $q->where('orders.status', 'completed')
              ->orWhere('orders.process_status', 'completed');
        })
        ->sum('order_items.quantity');

    $topProducts = OrderItem::select(
            'products.id',
            'products.name',
            'products.image',
            'products.price',
            DB::raw('SUM(order_items.quantity) as sold_count')
        )
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->whereBetween('orders.created_at', [$startDate, $endDate])
        ->where(function ($q) {
            $q->where('orders.status', 'completed')
              ->orWhere('orders.process_status', 'completed');
        })
        ->groupBy(
            'products.id',
            'products.name',
            'products.image',
            'products.price'
        )
        ->orderByDesc('sold_count')
        ->limit(3)
        ->get()
        ->map(function ($p) {
            if (!$p->image) {
                $p->image_url = 'https://via.placeholder.com/300';
            } elseif (str_starts_with($p->image, 'http://') || str_starts_with($p->image, 'https://')) {
                $p->image_url = $p->image;
            } else {
                $p->image_url = asset('storage/' . ltrim($p->image, '/'));
            }

            return $p;
        });


    $newUsers = User::whereBetween('created_at', [$startDate, $endDate])
        ->count();


    $avgOrderValue = $ordersCount > 0 ? $revenue / $ordersCount : 0;

    $bestSellerIds = $topProducts->pluck('id')->toArray();

    $reportStats = [
        'revenue' => $revenue,
        'orders_count' => $ordersCount,
        'products_sold' => $productsSold,
        'processing_orders' => $processingOrders,
        'avg_order_value' => $avgOrderValue,
        'new_users' => $newUsers,
    ];

    return view('report-page', compact(
        'reportStats',
        'topProducts',
        'bestSellerIds',
        'period',
        'startDate',
        'endDate'
    ));
}
}