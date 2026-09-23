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
        'promo_ends_at',
        'image',
        'additional_images',
        'colors',
        'sizes',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'promo_ends_at' => 'datetime',
        'colors' => 'array',
        'sizes' => 'array',
        'additional_images' => 'array',
        'is_active' => 'boolean',
    ];

    public function getColorsListAttribute(): array
    {
        if (is_array($this->colors)) {
            return array_values(array_filter(array_map('trim', $this->colors)));
        }
        if (is_string($this->colors) && !empty($this->colors)) {
            $decoded = json_decode($this->colors, true);
            if (is_array($decoded)) {
                return array_values(array_filter(array_map('trim', $decoded)));
            }
            return array_values(array_filter(array_map('trim', explode(',', $this->colors))));
        }
        return [];
    }

    public function getSizesListAttribute(): array
    {
        if (is_array($this->sizes)) {
            return array_values(array_filter(array_map('trim', $this->sizes)));
        }
        if (is_string($this->sizes) && !empty($this->sizes)) {
            $decoded = json_decode($this->sizes, true);
            if (is_array($decoded)) {
                return array_values(array_filter(array_map('trim', $decoded)));
            }
            return array_values(array_filter(array_map('trim', explode(',', $this->sizes))));
        }
        return [];
    }

    public function getAdditionalImagesUrlsAttribute(): array
    {
        $urls = [];
        $imgs = $this->additional_images;

        if (is_string($imgs)) {
            $imgs = json_decode($imgs, true) ?: [];
        }

        if (is_array($imgs)) {
            foreach ($imgs as $img) {
                if (!$img) continue;
                if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                    $urls[] = $img;
                } elseif (str_starts_with($img, 'storage/')) {
                    $urls[] = asset($img);
                } elseif (file_exists(public_path('storage/' . $img))) {
                    $urls[] = asset('storage/' . $img);
                } else {
                    $urls[] = asset($img);
                }
            }
        }
        return $urls;
    }

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

    public function getHasActivePromoAttribute(): bool
    {
        if (!$this->discount_price || $this->discount_price <= 0) {
            return false;
        }

        if ($this->promo_ends_at && $this->promo_ends_at->isPast()) {
            return false;
        }

        return true;
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->has_active_promo 
            ? (float) $this->discount_price 
            : (float) $this->price;
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedDiscountPriceAttribute(): ?string
    {
        if (!$this->has_active_promo) return null;
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

        if (str_starts_with($this->image, 'storage/')) {
            return asset($this->image);
        }

        if (file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }

        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return asset('storage/products/34.000.jpeg');
    }
}
