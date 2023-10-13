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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // khóa ngoại bảng users
            $table->string('date');
            $table->float("total");
            $table->string("payment_method");
            $table->string("address");
            $table->float("shipping_fee");
            $table->string("payment_status");
            $table->string("phone");
            $table->string('notes');
            $table->timestamps();

            // Định nghĩa khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_order');
    }
};
