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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('shipping_address')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // Foreign key for coupon_id will be added in a separate migration
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
