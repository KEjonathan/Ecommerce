<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'phone',
        'address',
        'profile_image',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
        ];
    }

    /**
     * Get the orders placed by the user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the carts belonging to the user.
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the product ratings given by the user.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rate::class);
    }

    /**
     * Get the orders assigned to the delivery person.
     */
    public function deliveryOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'delivery_person_id');
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    /**
     * Check if user is delivery personnel.
     */
    public function isDelivery(): bool
    {
        return $this->type === 'delivery';
    }

    /**
     * Check if user is customer.
     */
    public function isCustomer(): bool
    {
        return $this->type === 'customer';
    }

    /**
     * Get the custom notifications for the user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get unread notifications for the user.
     */
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }
}
