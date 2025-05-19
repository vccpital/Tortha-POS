<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('image_url'); // Store URL or path to image
            $table->boolean('is_primary')->default(false); // Marks main display image
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
