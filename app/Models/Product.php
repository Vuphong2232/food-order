<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'category',
        'is_active',
    ];

    protected $casts = [
        'price'     => 'integer',
        'is_active' => 'boolean',
    ];

    // Danh mục tiếng Việt
    public function getCategoryLabelAttribute()
    {
        return match($this->category) {
            'mon-chinh'    => 'Món chính',
            'mon-an-vat'   => 'Ăn vặt',
            'do-uong'      => 'Đồ uống',
            'trang-mieng'  => 'Tráng miệng',
            default        => 'Khác',
        };
    }

    // Định dạng giá
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . '₫';
    }
}