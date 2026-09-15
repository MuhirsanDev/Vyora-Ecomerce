<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (Category $category) {
            if ($category->image && !str_contains($category->image, 'sample/')) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($category->image)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($category->image);
                } elseif (file_exists(public_path($category->image))) {
                    @unlink(public_path($category->image));
                }
            }
        });
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('images/sample/silk_blouse.jpg');
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (str_starts_with($this->image, 'categories/')) {
            if (file_exists(public_path('storage/' . $this->image))) {
                return asset('storage/' . $this->image);
            }
            return asset('images/sample/silk_blouse.jpg');
        }

        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return asset('images/sample/silk_blouse.jpg');
    }
}
