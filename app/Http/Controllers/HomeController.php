<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'all');
        $price = $request->get('price');

        $bestSellerProducts = OrderItem::select(
                'products.id',
                'products.name',
                'products.image',
                'products.price',
                'products.category_id',
                DB::raw('SUM(order_items.quantity) as sold_count')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->groupBy(
                'products.id',
                'products.name',
                'products.image',
                'products.price',
                'products.category_id'
            )
            ->orderByDesc('sold_count')
            ->take(3)
            ->get();

        $bestSellerIds = $bestSellerProducts->pluck('id')->toArray();

        $categories = Category::where('is_active', true)
            ->orderBy('id')
            ->get();

        $query = Product::with('category')
            ->where('is_active', true)
            ->orderBy('id');

        if ($category && $category !== 'all') {
            if ($category === 'best-seller') {
                $query->whereIn('id', $bestSellerIds);
            } else {
                $query->whereHas('category', function ($q) use ($category) {
                    $q->where('slug', $category)
                      ->where('is_active', true);
                });
            }
        }

        if ($price) {
            [$min, $max] = array_pad(explode('-', $price), 2, null);

            if (is_numeric($min)) {
                $query->where('price', '>=', (int) $min);
            }

            if (is_numeric($max)) {
                $query->where('price', '<=', (int) $max);
            }
        }

        $products = $query->paginate(15)->withQueryString();

        return view('home', compact(
            'products',
            'bestSellerProducts',
            'bestSellerIds',
            'categories',
            'category',
            'price'
        ));
    }

    public function manage()
    {
        return view('manage');
    }
}