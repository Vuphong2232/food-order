<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Lấy danh sách (cho trang quản lý)
    public function index()
    {
        return response()->json([
            'data' => Product::orderBy('id')->get()
        ]);
    }

    // Thêm mới
    public function store(Request $request)
{
    $validated = $request->validate([
        'name'        => 'required|string|max:255',
        'price'       => 'required|integer|min:0',
        'description' => 'nullable|string|max:255',
        'image'       => 'nullable|string|max:500',
        'image_file'  => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        'category'    => 'nullable|string|max:50',
        'is_active'   => 'nullable|boolean',
    ]);

    if ($request->hasFile('image_file')) {
        $path = $request->file('image_file')->store('products', 'public');
        $validated['image'] = $path;
    }

    $product = Product::create([
        'name'        => $validated['name'],
        'price'       => $validated['price'],
        'description' => $validated['description'] ?? null,
        'image'       => $validated['image'] ?? null,
        'category'    => $validated['category'] ?? null,
        'is_active'   => isset($validated['is_active']) ? $validated['is_active'] : 1,
    ]);

    return response()->json([
        'message' => 'Thêm sản phẩm thành công!',
        'data'    => $product
    ], 201);
}

    // Cập nhật
   public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'name'        => 'required|string|max:255',
        'price'       => 'required|integer|min:0',
        'description' => 'nullable|string|max:255',
        'image'       => 'nullable|string|max:500',
        'image_file'  => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        'category'    => 'nullable|string|max:50',
        'is_active'   => 'nullable|boolean',
    ]);

    if ($request->hasFile('image_file')) {
        if ($product->image && !str_starts_with($product->image, 'http://') && !str_starts_with($product->image, 'https://')) {
            Storage::disk('public')->delete($product->image);
        }

        $path = $request->file('image_file')->store('products', 'public');
        $validated['image'] = $path;
    }

    $product->update([
        'name'        => $validated['name'],
        'price'       => $validated['price'],
        'description' => $validated['description'] ?? null,
        'image'       => $validated['image'] ?? $product->image,
        'category'    => $validated['category'] ?? null,
        'is_active'   => isset($validated['is_active']) ? $validated['is_active'] : $product->is_active,
    ]);

    return response()->json([
        'message' => 'Cập nhật sản phẩm thành công!',
        'data'    => $product
    ]);
}

    // Xóa
    public function destroy(Product $product)
{
    if ($product->image && !str_starts_with($product->image, 'http://') && !str_starts_with($product->image, 'https://')) {
        Storage::disk('public')->delete($product->image);
    }

    $product->delete();

    return response()->json([
        'message' => 'Xóa sản phẩm thành công!'
    ]);
}

public function show(Product $product)
{
    $products = Product::all();

    return view('home', [
        'products' => $products,
        'selectedProduct' => $product
    ]);
}

public function detail(Product $product)
{
    $bestSellerProducts = OrderItem::select(
            'products.id',
            'products.name',
            'products.image',
            'products.price',
            'products.category',
            DB::raw('SUM(order_items.quantity) as sold_count')
        )
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('orders.status', 'completed')
        ->groupBy('products.id', 'products.name', 'products.image', 'products.price', 'products.category')
        ->orderByDesc('sold_count')
        ->take(10)
        ->get();

    $bestSellerIds = $bestSellerProducts->pluck('id')->toArray();

    return view('home', [
        'products' => collect(),
        'selectedProduct' => $product,
        'bestSellerProducts' => $bestSellerProducts,
        'bestSellerIds' => $bestSellerIds,
    ]);
}
}