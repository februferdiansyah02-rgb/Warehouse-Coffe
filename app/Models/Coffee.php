<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coffee extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category_id',
        'origin',
        'process',
        'form',
        'grade',
        'stock',
        'unit',
        'purchase_price',
        'description',
        'status',
    ];

    protected $casts = [
        'stock' => 'decimal:2',
        'purchase_price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
