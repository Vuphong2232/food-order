<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            ['name' => 'Phở Bò Tái',        'price' => 55000, 'description' => 'Nước dùng đậm đà',        'image' => 'https://picsum.photos/seed/pho-bo/400/400.jpg',      'category' => 'mon-chinh'],
            ['name' => 'Bánh Mì Thịt',       'price' => 30000, 'description' => 'Giòn rụm thơm lừng',       'image' => 'https://picsum.photos/seed/banh-mi/400/400.jpg',     'category' => 'mon-an-vat'],
            ['name' => 'Cơm Tấm Sườn',       'price' => 45000, 'description' => 'Sườn nướng than hoa',       'image' => 'https://picsum.photos/seed/com-tam/400/400.jpg',     'category' => 'mon-chinh'],
            ['name' => 'Bún Chả Hà Nội',     'price' => 50000, 'description' => 'Chả nướng thơm ngon',       'image' => 'https://picsum.photos/seed/bun-cha/400/400.jpg',     'category' => 'mon-chinh'],
            ['name' => 'Gỏi Cuốn Tôm',       'price' => 35000, 'description' => 'Tôm thịt tươi sống',        'image' => 'https://picsum.photos/seed/goi-cuon/400/400.jpg',    'category' => 'mon-an-vat'],
            ['name' => 'Cà Phê Sữa Đá',      'price' => 25000, 'description' => 'Đậm vị thơm lừng',         'image' => 'https://picsum.photos/seed/ca-phe/400/400.jpg',      'category' => 'do-uong'],
            ['name' => 'Bánh Xèo',            'price' => 40000, 'description' => 'Giòn tan ngập nước mắm',   'image' => 'https://picsum.photos/seed/banh-xeo/400/400.jpg',    'category' => 'mon-an-vat'],
            ['name' => 'Mì Quảng',            'price' => 48000, 'description' => 'Mì vàng sợi to',           'image' => 'https://picsum.photos/seed/mi-quang/400/400.jpg',    'category' => 'mon-chinh'],
            ['name' => 'Trà Đào Cam Sả',      'price' => 30000, 'description' => 'Thanh mát tỏa hương',       'image' => 'https://picsum.photos/seed/tra-dao/400/400.jpg',     'category' => 'do-uong'],
            ['name' => 'Chè Thập Cẩm',        'price' => 28000, 'description' => 'Đầy đủ topping',            'image' => 'https://picsum.photos/seed/che-thap/400/400.jpg',    'category' => 'trang-mieng'],
        ];

        foreach ($products as $item) {
            Product::create($item);
        }
    }
}