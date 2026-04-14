<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class CartController extends Controller
{
     public function store(Request $request)
{
    if (!Auth::check()) {
        return response()->json([
            'success' => false,
            'message' => 'Vui lòng đăng nhập'
        ]);
    }

    $product = Product::find($request->product_id);

    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Sản phẩm không tồn tại'
        ]);
    }

    $cartItem = CartItem::where('user_id', Auth::id())
        ->where('product_id', $product->id)
        ->first();

   if ($cartItem) {
        return response()->json([
            'success' => false,
            'message' => 'Sản phẩm đã có trong giỏ hàng'
        ]);
    }

    $qty = $request->quantity ?? 1;
    
    CartItem::create([
        'user_id' => Auth::id(),
        'product_id' => $product->id,
        'quantity' => $request->quantity ?? 1,
        'image' => $product->image,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Đã thêm ' . $qty . ' sản phẩm vào giỏ hàng!'
    ]);
}

    public function index()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return view('cart.index', compact('cartItems'));
    }

    public function getCart()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json($cartItems);
    }

    public function updateQty(Request $request)
{
    $item = CartItem::find($request->cart_item_id);

    if (!$item) {
        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy sản phẩm'
        ]);
    }

    $item->quantity += $request->delta;

    if ($item->quantity <= 0) {
        $item->delete();
    } else {
        $item->save();
    }

    return response()->json([
        'success' => true,
        'message' => 'Cập nhật giỏ hàng'
    ]);
}

    public function deleteItem(Request $request)
    {
        $item = CartItem::find($request->cart_item_id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy'
            ]);
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm'
        ]);
    }

    public function deleteAll()
    {
        CartItem::where('user_id', Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa toàn bộ giỏ hàng'
        ]);
    }
   
}