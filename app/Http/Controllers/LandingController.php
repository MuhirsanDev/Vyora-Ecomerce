<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        // Active selected category object
        $activeCategory = null;

        // Search filter (Safe against SQL Injection via Eloquent parameter binding)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter (Exact relationship matching)
        if ($request->filled('category')) {
            $categorySlug = $request->input('category');
            $activeCategory = Category::where('slug', $categorySlug)->first();

            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }

        // Color filter
        if ($request->filled('color')) {
            $color = $request->input('color');
            $query->where('colors', 'like', "%\"{$color}\"%");
        }

        // Size filter
        if ($request->filled('size')) {
            $size = $request->input('size');
            $query->where('sizes', 'like', "%\"{$size}\"%");
        }

        $products = $query->latest()->paginate(50)->withQueryString();
        $categories = Category::withCount(['products' => function ($q) {
            $q->where('is_active', true);
        }])->get();
        
        $whatsappNumber = Setting::get('whatsapp_number', '6281234567890');

        return view('landing.index', compact('products', 'categories', 'activeCategory', 'whatsappNumber'));
    }

    public function show($slug)
    {
        $product = Product::with('category')->where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        $whatsappNumber = Setting::get('whatsapp_number', '6281234567890');
        $storeName = Setting::get('store_name', 'VYORA');

        // Format direct WhatsApp URL for single product inquiry
        $message = "Halo {$storeName}, saya mau tanya / pesan produk ini:\n\n"
            . "*{$product->name}*\n"
            . "Harga: {$product->formatted_price}\n"
            . "Link: " . route('products.show', $product->slug);

        $directWaUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);

        return view('landing.show', compact('product', 'relatedProducts', 'directWaUrl'));
    }
}
