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
        Setting::set('store_address', 'Jl. Raya Rangkasbitung No. 8, Kareo, Serang, Kabupaten Serang, Banten 42177');
        Setting::set('whatsapp_number', '6281994578184');
        Setting::set('whatsapp_message', 'Halo Admin Vyora, saya tertarik untuk memesan produk berikut:');

        // 3. Seed Categories (Using real product images)
        $categories = [
            ['name' => 'Pakaian Casual', 'image' => 'products/34.000.jpeg'],
            ['name' => 'Atasan & Dress', 'image' => 'products/55.000.jpeg'],
            ['name' => 'Koleksi Premium', 'image' => 'products/60.000.jpeg'],
        ];

        // Clean up old categories & products
        Product::query()->delete();
        Category::query()->delete();

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

        // 4. Seed Real Products from User Downloads
        $products = [
            [
                'name' => 'Vyora Top Rp 34.000 (Model A)',
                'price' => 34000,
                'discount_price' => null,
                'description' => 'Pakaian atasan wanita Vyora dengan bahan adem, nyaman dipakai sehari-hari.',
                'image' => 'products/34.000.jpeg',
                'category_id' => $catModels[0]->id,
                'stock' => 20,
            ],
            [
                'name' => 'Vyora Top Rp 34.000 (Model B)',
                'price' => 34000,
                'discount_price' => null,
                'description' => 'Pakaian atasan casual Vyora dengan motif & warna pilihan menarik.',
                'image' => 'products/34.000 - 1.jpeg',
                'category_id' => $catModels[0]->id,
                'stock' => 20,
            ],
            [
                'name' => 'Vyora Casual Top Rp 37.000',
                'price' => 37000,
                'discount_price' => null,
                'description' => 'Atasan wanita gaya modern kekinian dari koleksi Vyora Store.',
                'image' => 'products/37.000.jpeg',
                'category_id' => $catModels[0]->id,
                'stock' => 15,
            ],
            [
                'name' => 'Vyora Dress Chic Rp 55.000 (Model A)',
                'price' => 55000,
                'discount_price' => null,
                'description' => 'Pakaian wanita gaya chic premium berbahan katun berkualitas tinggi.',
                'image' => 'products/55.000.jpeg',
                'category_id' => $catModels[1]->id,
                'stock' => 15,
            ],
            [
                'name' => 'Vyora Dress Chic Rp 55.000 (Model B)',
                'price' => 55000,
                'discount_price' => null,
                'description' => 'Busana pilihan Vyora Store dengan potongan fit dan jahitan rapi.',
                'image' => 'products/55.000 - 1.jpeg',
                'category_id' => $catModels[1]->id,
                'stock' => 15,
            ],
            [
                'name' => 'Vyora Dress Chic Rp 55.000 (Model C)',
                'price' => 55000,
                'discount_price' => null,
                'description' => 'Busana wanita cantik dan kekinian untuk melengkapi gaya berbusana Anda.',
                'image' => 'products/55.000 - 2.jpeg',
                'category_id' => $catModels[1]->id,
                'stock' => 15,
            ],
            [
                'name' => 'Vyora Premium Outfit Rp 60.000 (Model A)',
                'price' => 60000,
                'discount_price' => null,
                'description' => 'Pakaian eksklusif Vyora dengan kualitas kain super lembut dan tidak menerawang.',
                'image' => 'products/60.000.jpeg',
                'category_id' => $catModels[2]->id,
                'stock' => 10,
            ],
            [
                'name' => 'Vyora Premium Outfit Rp 60.000 (Model B)',
                'price' => 60000,
                'discount_price' => null,
                'description' => 'Outfit signature Vyora Store paling terfavorit dan elegan.',
                'image' => 'products/60.000 - 1.jpeg',
                'category_id' => $catModels[2]->id,
                'stock' => 10,
            ],
            [
                'name' => 'Vyora Premium Outfit Rp 60.000 (Model C)',
                'price' => 60000,
                'discount_price' => null,
                'description' => 'Busana favorit Vyora Store dengan kombinasi style anggun dan simpel.',
                'image' => 'products/60.000 - 2.jpeg',
                'category_id' => $catModels[2]->id,
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
