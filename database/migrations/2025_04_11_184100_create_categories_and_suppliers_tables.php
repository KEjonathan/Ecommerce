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
        // Create categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
        
        // Add foreign key constraint after table creation
        Schema::table('categories', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
        });
        
        // Create suppliers table
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('company_name');
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
        
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
        });
        
        Schema::dropIfExists('categories');
    }
};
