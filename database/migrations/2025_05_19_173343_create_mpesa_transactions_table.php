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
        Schema::create('mpesa_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('merchantRequestId')->nullable();
            $table->string('checkoutRequestId')->nullable();
            $table->string('mpesaReceiptNumber')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->dateTime('transactionDate');
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mpesa_transactions', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
        });

        Schema::dropIfExists('mpesa_transactions');
    }
};
