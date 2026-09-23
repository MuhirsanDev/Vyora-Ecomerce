<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(50);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'promo_ends_at' => ['nullable', 'date'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'additional_images' => ['nullable', 'array'],
            'additional_images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'colors' => ['nullable', 'array'],
            'colors.*' => ['string', 'max:50'],
            'custom_colors' => ['nullable', 'string'],
            'sizes' => ['nullable', 'array'],
            'sizes.*' => ['string', 'max:50'],
            'custom_sizes' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $colors = $request->input('colors', []);
        if (!is_array($colors)) {
            $colors = [];
        }
        if ($request->filled('custom_colors')) {
            $custom = explode(',', $request->input('custom_colors'));
            foreach ($custom as $c) {
                $trimmed = trim($c);
                if ($trimmed !== '' && !in_array($trimmed, $colors)) {
                    $colors[] = $trimmed;
                }
            }
        }
        $validated['colors'] = array_values(array_unique(array_filter($colors)));

        $sizes = $request->input('sizes', []);
        if (!is_array($sizes)) {
            $sizes = [];
        }
        if ($request->filled('custom_sizes')) {
            $customSizes = explode(',', $request->input('custom_sizes'));
            foreach ($customSizes as $s) {
                $trimmed = trim($s);
                if ($trimmed !== '' && !in_array($trimmed, $sizes)) {
                    $sizes[] = $trimmed;
                }
            }
        }
        $validated['sizes'] = array_values(array_unique(array_filter($sizes)));

        // Auto-generate slug from name in background
        $slug = Str::slug($validated['name']);
        if (Product::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . Str::random(5);
        }
        $validated['slug'] = $slug;
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $additionalPaths = [];
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $file) {
                $additionalPaths[] = $file->store('products', 'public');
            }
        }
        $validated['additional_images'] = $additionalPaths;

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'promo_ends_at' => ['nullable', 'date'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'additional_images' => ['nullable', 'array'],
            'additional_images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'remove_additional_images' => ['nullable', 'array'],
            'colors' => ['nullable', 'array'],
            'colors.*' => ['string', 'max:50'],
            'custom_colors' => ['nullable', 'string'],
            'sizes' => ['nullable', 'array'],
            'sizes.*' => ['string', 'max:50'],
            'custom_sizes' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $colors = $request->input('colors', []);
        if (!is_array($colors)) {
            $colors = [];
        }
        if ($request->filled('custom_colors')) {
            $custom = explode(',', $request->input('custom_colors'));
            foreach ($custom as $c) {
                $trimmed = trim($c);
                if ($trimmed !== '' && !in_array($trimmed, $colors)) {
                    $colors[] = $trimmed;
                }
            }
        }
        $validated['colors'] = array_values(array_unique(array_filter($colors)));

        $sizes = $request->input('sizes', []);
        if (!is_array($sizes)) {
            $sizes = [];
        }
        if ($request->filled('custom_sizes')) {
            $customSizes = explode(',', $request->input('custom_sizes'));
            foreach ($customSizes as $s) {
                $trimmed = trim($s);
                if ($trimmed !== '' && !in_array($trimmed, $sizes)) {
                    $sizes[] = $trimmed;
                }
            }
        }
        $validated['sizes'] = array_values(array_unique(array_filter($sizes)));

        if ($validated['name'] !== $product->name) {
            $slug = Str::slug($validated['name']);
            if (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $slug . '-' . Str::random(5);
            }
            $validated['slug'] = $slug;
        }

        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $existingImages = $product->additional_images ?: [];
        if (!is_array($existingImages)) {
            $existingImages = [];
        }

        if ($request->has('remove_additional_images')) {
            $toRemove = $request->input('remove_additional_images');
            $existingImages = array_filter($existingImages, function($img) use ($toRemove) {
                if (in_array($img, $toRemove)) {
                    if (Storage::disk('public')->exists($img)) {
                        Storage::disk('public')->delete($img);
                    }
                    return false;
                }
                return true;
            });
            $existingImages = array_values($existingImages);
        }

        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $file) {
                $existingImages[] = $file->store('products', 'public');
            }
        }
        $validated['additional_images'] = array_values($existingImages);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        if (is_array($product->additional_images)) {
            foreach ($product->additional_images as $img) {
                if ($img && Storage::disk('public')->exists($img)) {
                    Storage::disk('public')->delete($img);
                }
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
