<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'image',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (Product $product) {
            if ($product->image && !str_contains($product->image, 'sample/')) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($product->image)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
                } elseif (file_exists(public_path($product->image))) {
                    @unlink(public_path($product->image));
                }
            }
        });
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->discount_price && $this->discount_price > 0 
            ? (float) $this->discount_price 
            : (float) $this->price;
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedDiscountPriceAttribute(): ?string
    {
        if (!$this->discount_price) return null;
        return 'Rp ' . number_format($this->discount_price, 0, ',', '.');
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('storage/products/34.000.jpeg');
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (str_starts_with($this->image, 'products/')) {
            if (file_exists(public_path('storage/' . $this->image))) {
                return asset('storage/' . $this->image);
            }
            return asset('storage/products/34.000.jpeg');
        }

        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return asset('storage/products/34.000.jpeg');
    }
}
