<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderItem;
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
                'products.category',
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
                'products.category'
            )
            ->orderByDesc('sold_count')
            ->take(10)
            ->get();

        $bestSellerIds = $bestSellerProducts->pluck('id')->toArray();

        $query = Product::where('is_active', true)->orderBy('id');

        if ($category && $category !== 'all') {
            if ($category === 'best-seller') {
                $query->whereIn('id', $bestSellerIds);
            } else {
                $query->where('category', $category);
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
            'category',
            'price'
        ));
    }

    public function manage()
    {
        return view('manage');
    }
}