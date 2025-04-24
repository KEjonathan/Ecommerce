<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'shipping_address',
        'subtotal',
        'coupon_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
    ];

    /**
     * Get the user who owns the cart.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items in the cart.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the coupon applied to the cart.
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Calculate the total with any applied coupon discount.
     */
    public function getTotalAttribute(): float
    {
        $total = $this->subtotal;
        
        if ($this->coupon) {
            if ($this->coupon->type === 'percentage') {
                $total = $total - ($total * ($this->coupon->value / 100));
            } else {
                $total = $total - $this->coupon->value;
            }
        }
        
        return max(0, $total);
    }
}
