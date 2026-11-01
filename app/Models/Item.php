<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $fillable = ['item_name', 'category_id', 'supplier_id', 'stock_kg', 'minimum_stock', 'price_per_kg'];

    protected $casts = [
        'stock_kg' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'price_per_kg' => 'decimal:2',
    ];

    /**
     * Scope: hanya item yang stoknya di bawah ambang batas minimum (stok menipis).
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_kg', '<', 'minimum_stock');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
