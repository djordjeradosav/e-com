<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'low_stock_threshold',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
    ];

    /**
     * Get cart items for this product
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get order items for this product
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Check if product is low on stock
     */
    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->low_stock_threshold;
    }

    /**
     * Check if product is in stock
     */
    public function isInStock(int $quantity = 1): bool
    {
        return $this->stock_quantity >= $quantity;
    }

    /**
     * Decrease stock quantity
     */
    public function decreaseStock(int $quantity): void
    {
        $this->decrement('stock_quantity', $quantity);
    }
}