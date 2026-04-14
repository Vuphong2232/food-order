<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders'; // Đảm bảo tên bảng đúng

    // QUAN TRỌNG: Thêm 'user_id' vào mảng này
    protected $fillable = [
        'code',
        'user_id', // <--- Đảm bảo dòng này có mặt
        'name',
        'phone',
        'email',
        'address',
        'note',
        'payment_method',
        'total_amount',
        'status',
        'process_status',
    ];

   public function items()
{
    return $this->hasMany(OrderItem::class, 'order_id');
}


public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}
}