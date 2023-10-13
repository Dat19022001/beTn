<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id')->select('id', 'name');
    }
    protected $fillable = [
        "name",
        "image",
        "description",
        "price",
        "category_id",
        "producer",
    ];
}
