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
        Schema::create('cartItems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id'); // Khóa ngoại đến bảng carts
            $table->unsignedBigInteger('product_id'); // Khóa ngoại đến bảng products
            $table->integer('quantity');
            $table->timestamps();

            // Định nghĩa các khóa ngoại
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_cart_item');
    }
};
