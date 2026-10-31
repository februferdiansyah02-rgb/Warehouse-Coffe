<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutgoingGoodItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'outgoing_good_id',
        'coffee_id',
        'category_id',
        'name',
        'available_stock',
        'qty',
        'unit',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'available_stock' => 'decimal:2',
        'qty' => 'decimal:2',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function outgoingGood(): BelongsTo
    {
        return $this->belongsTo(OutgoingGood::class);
    }

    public function coffee(): BelongsTo
    {
        return $this->belongsTo(Coffee::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
