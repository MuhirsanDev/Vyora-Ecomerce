<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'link',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Slider $slider) {
            if ($slider->image && !str_contains($slider->image, 'sample/')) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($slider->image)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($slider->image);
                } elseif (file_exists(public_path($slider->image))) {
                    @unlink(public_path($slider->image));
                }
            }
        });
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('images/banner-image.jpg');
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (str_starts_with($this->image, 'sliders/')) {
            return asset('storage/' . $this->image);
        }

        return asset($this->image);
    }
}
