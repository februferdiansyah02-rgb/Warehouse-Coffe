<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OutgoingGood extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'date',
        'purpose',
        'recipient_name',
        'reference_no',
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

    public function items(): HasMany
    {
        return $this->hasMany(OutgoingGoodItem::class);
    }

    public function scopeByPurpose($query, string $purpose)
    {
        return $query->where('purpose', $purpose);
    }

    public function scopeByDateRange($query, ?string $from, ?string $to)
    {
        if ($from) {
            $query->whereDate('date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('date', '<=', $to);
        }
        return $query;
    }
}
