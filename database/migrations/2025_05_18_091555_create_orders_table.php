<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('user_id')->nullable(); // cashier who created
            $table->unsignedBigInteger('customer_id')->nullable(); // registered customer
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('status', ['cart', 'pending', 'paid', 'cancelled', 'refunded'])->default('cart');
            $table->enum('payment_status', ['unpaid', 'partially_paid', 'paid'])->default('unpaid');
            $table->boolean('is_draft')->default(true);
            $table->dateTime('due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
