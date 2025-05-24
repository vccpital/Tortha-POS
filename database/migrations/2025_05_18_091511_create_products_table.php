<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id'); // FK defined separately
            $table->string('name');
            $table->text('description');
            $table->string('sku')->unique();
            $table->string('barcode')->unique();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock_qty')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
