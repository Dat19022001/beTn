<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orderDetails', function (Blueprint $table) {
            $table->id();
            $table-> unsignedBigInteger('order_id');
            $table -> string('product_id');
            $table -> string('product_name');
            $table -> float('product_price');
            $table -> integer('quantity');
            $table -> float('total');
            $table->timestamps();
            $table -> foreign("order_id") -> references("id") -> on('orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_order_deatail');
    }
};
