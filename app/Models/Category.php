<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function coffees(): HasMany
    {
        return $this->hasMany(Coffee::class);
    }

    public function getTotalStockAttribute(): float
    {
        $coffeeStock = (float) $this->coffees()->sum('stock');
        if ($coffeeStock > 0) {
            return $coffeeStock;
        }
        return (float) $this->items()->sum('stock_kg');
    }

    public function getTotalVariantsAttribute(): int
    {
        $coffeeCount = $this->coffees()->count();
        if ($coffeeCount > 0) {
            return $coffeeCount;
        }
        return $this->items()->count();
    }
}
