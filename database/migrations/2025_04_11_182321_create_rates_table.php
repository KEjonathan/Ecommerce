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
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('rating')->comment('Rating from 1 to 5');
            $table->text('review')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // Product reference will be added after products table is created
            $table->unique(['user_id', 'product_id']); // Prevent duplicate reviews
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
