<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, ?string $default = null): ?string
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function getLogoUrl(): string
    {
        $logo = static::get('store_logo');
        if ($logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($logo)) {
            return asset('storage/' . $logo);
        }
        return asset('images/logo.svg');
    }

    public static function getFaviconUrl(): string
    {
        $logo = static::get('store_logo');
        if ($logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($logo)) {
            return asset('storage/' . $logo);
        }
        return asset('images/favicon.svg');
    }
}
