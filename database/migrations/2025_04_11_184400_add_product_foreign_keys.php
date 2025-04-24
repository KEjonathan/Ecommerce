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
        // Add foreign key references to products from cart_items
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        // Add foreign key references to products from order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        // Add foreign key references to products from rates
        Schema::table('rates', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove foreign key references to products from cart_items
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        // Remove foreign key references to products from order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        // Remove foreign key references to products from rates
        Schema::table('rates', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
    }
};
