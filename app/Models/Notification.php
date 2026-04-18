<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['type','user_id', 'title', 'message', 'data', 'is_read'];
    
    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean'
    ];
}
