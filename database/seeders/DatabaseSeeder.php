<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Admin & Customer User
        User::updateOrCreate(
            ['email' => 'admin@vyora.com'],
            [
                'name' => 'Admin Vyora',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@vyora.com'],
            [
                'name' => 'Pelanggan',
                'password' => Hash::make('user123'),
                'role' => 'customer',
                'phone' => '089876543210',
            ]
        );

        // 2. Seed Settings
        Setting::set('store_name', 'Vyora Fashion Store');
        Setting::set('store_email', 'info@vyorastore.com');
        Setting::set('store_address', 'Jakarta, Indonesia');
        Setting::set('whatsapp_number', '6281234567890');
        Setting::set('whatsapp_message', 'Halo Admin Vyora, saya tertarik untuk memesan produk berikut:');

        // 3. Seed Categories
        $categories = [
            ['name' => 'Baju Wanita', 'image' => 'images/sample/silk_blouse.jpg'],
            ['name' => 'Pakaian Casual', 'image' => 'images/sample/denim_jacket.jpg'],
            ['name' => 'Aksesoris', 'image' => 'images/sample/leather_handbag.jpg'],
            ['name' => 'Koleksi Musim Panas', 'image' => 'images/sample/summer_dress.jpg'],
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[] = Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'image' => $cat['image'],
                ]
            );
        }

        // 4. Seed Initial Sample Products
        $products = [
            [
                'name' => 'Blouse Silk Elegant',
                'price' => 185000,
                'discount_price' => 150000,
                'description' => 'Blouse sutra elegan berbahan lembut dan dingin, cocok untuk acara formal maupun santai.',
                'image' => 'images/sample/silk_blouse.jpg',
                'category_id' => $catModels[0]->id,
                'stock' => 15,
            ],
            [
                'name' => 'Casual Denim Jacket',
                'price' => 275000,
                'discount_price' => 245000,
                'description' => 'Jaket denim bergaya modern dengan potongan fit yang nyaman digunakan seharian.',
                'image' => 'images/sample/denim_jacket.jpg',
                'category_id' => $catModels[1]->id,
                'stock' => 20,
            ],
            [
                'name' => 'Summer Floral Dress',
                'price' => 210000,
                'discount_price' => 189000,
                'description' => 'Gaun floral musim panas dengan desain feminin dan warna cerah memikat.',
                'image' => 'images/sample/summer_dress.jpg',
                'category_id' => $catModels[3]->id,
                'stock' => 12,
            ],
            [
                'name' => 'Classic Leather Handbag',
                'price' => 350000,
                'discount_price' => 299000,
                'description' => 'Tas tangan kulit sintetis premium dengan kompartemen luas dan jahitan rapi.',
                'image' => 'images/sample/leather_handbag.jpg',
                'category_id' => $catModels[2]->id,
                'stock' => 8,
            ],
            [
                'name' => 'Modern Minimalist Top',
                'price' => 140000,
                'discount_price' => 120000,
                'description' => 'Atasan gaya minimalis dengan bahan katun berkualitas tinggi.',
                'image' => 'images/sample/silk_blouse.jpg',
                'category_id' => $catModels[0]->id,
                'stock' => 25,
            ],
            [
                'name' => 'Oversized Knit Sweater',
                'price' => 230000,
                'discount_price' => 199000,
                'description' => 'Sweater rajut rajut tebal dan hangat dengan style oversized terkini.',
                'image' => 'images/sample/denim_jacket.jpg',
                'category_id' => $catModels[1]->id,
                'stock' => 10,
            ],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(
                ['slug' => Str::slug($p['name'])],
                [
                    'category_id' => $p['category_id'],
                    'name' => $p['name'],
                    'description' => $p['description'],
                    'price' => $p['price'],
                    'discount_price' => $p['discount_price'],
                    'image' => $p['image'],
                    'stock' => $p['stock'],
                    'is_active' => true,
                ]
            );
        }
    }
}
