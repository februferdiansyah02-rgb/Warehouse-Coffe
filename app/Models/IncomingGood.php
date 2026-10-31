<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IncomingGood extends Model
{
    protected $fillable = [
        'code',
        'date',
        'supplier_id',
        'supplier',
        'invoice',
        'officer',
        'note',
        'total_kg',
        'total_price',
    ];

    protected $casts = [
        'date' => 'date',
        'total_kg' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(IncomingGoodItem::class);
    }
}
