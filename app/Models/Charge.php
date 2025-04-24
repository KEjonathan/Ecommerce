<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'amount',
        'description',
        'status',
        'is_percentage',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'status' => 'boolean',
        'is_percentage' => 'boolean',
    ];

    /**
     * Calculate the charge amount based on a given subtotal.
     */
    public function calculateAmount(float $subtotal): float
    {
        if (!$this->status) {
            return 0;
        }

        if ($this->is_percentage) {
            return $subtotal * ($this->amount / 100);
        }

        return $this->amount;
    }
}
