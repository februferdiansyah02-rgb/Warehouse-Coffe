<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncomingGoodItem extends Model
{
    protected $fillable = [
        'incoming_good_id',
        'coffee_id',
        'category_id',
        'name',
        'qty',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function incomingGood(): BelongsTo
    {
        return $this->belongsTo(IncomingGood::class);
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
