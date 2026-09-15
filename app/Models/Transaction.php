<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'user_id',
        'customer_name',
        'customer_phone',
        'product_name',
        'quantity',
        'price_per_unit',
        'total_amount',
        'payment_method',
        'status',
        'transaction_date',
        'notes',
    ];

    protected $casts = [
        'price_per_unit' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'transaction_date' => 'date',
        'quantity' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedTotalAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getFormattedPricePerUnitAttribute(): string
    {
        return 'Rp ' . number_format($this->price_per_unit, 0, ',', '.');
    }
}
