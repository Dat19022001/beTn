<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    protected $fillable = [
        'user_id',
        "date",
        "total",
        'payment_method',
        'address',
        'shipping_fee',
        'phone',
        'note',
        'payment_status'
    ];
}
