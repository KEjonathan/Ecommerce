<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'image',
        'is_morning_available',
        'is_afternoon_available',
        'is_evening_available',
        'category_id',
        'supplier_id',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean',
        'is_morning_available' => 'boolean',
        'is_afternoon_available' => 'boolean',
        'is_evening_available' => 'boolean',
    ];

    /**
     * Get the category of the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the supplier of the product.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the cart items containing this product.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the order items containing this product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the ratings for this product.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rate::class);
    }

    /**
     * Get the average rating.
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->ratings()->avg('rating') ?? 0;
    }
}
