<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = 'orderDetails';
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    protected $fillable = [
        'product_id',
        "product_name",
        'product_price',
        'quantity',
        'total',
        'order_id'
    ];
}
