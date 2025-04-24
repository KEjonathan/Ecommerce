<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'type',
        'value',
        'usage_limit',
        'usage_count',
        'min_purchase',
        'expires_at',
        'status',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'expires_at' => 'datetime',
        'status' => 'boolean',
    ];

    /**
     * Get the orders that used this coupon.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the carts that have this coupon.
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Check if the coupon is valid for a given subtotal.
     */
    public function isValidFor(float $subtotal): bool
    {
        if (!$this->status) {
            return false;
        }

        if ($this->expires_at && now()->greaterThan($this->expires_at)) {
            return false;
        }

        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        if ($this->min_purchase && $subtotal < $this->min_purchase) {
            return false;
        }

        return true;
    }
}
