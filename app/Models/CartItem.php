<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product()
        {
            return $this->belongsTo(Product::class);
        }

    public function getSubTotalAttribute()
    {
        return $this->quantity * ($this->product ? $this->product->price : 0);
    }
}