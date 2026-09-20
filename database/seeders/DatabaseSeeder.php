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
        Setting::set('store_email', null);
        Setting::set('store_address', 'Jl. Raya Rangkasbitung No. 8, Kareo, Serang, Kabupaten Serang, Banten 42177');
        Setting::set('whatsapp_number', '6281994578184');
        Setting::set('whatsapp_message', 'Halo Admin Vyora, saya tertarik untuk memesan produk berikut:');

        // 3. Seed Categories (Bag & Wallet Categories)
        $categories = [
            ['name' => 'Tas Bahu & Selempang', 'image' => 'products/34.000 - 1.jpeg'],
            ['name' => 'Dompet Wanita', 'image' => 'products/37.000.jpeg'],
            ['name' => 'Tas Handbag Elegan', 'image' => 'products/55.000.jpeg'],
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

        // 4. Seed Real Bag & Wallet Products with Aesthetic Product Names & Promo Prices (+15.000 Strikethrough Price)
        $products = [
            [
                'name' => 'Tas Bahu Ribbon Bow Elegance',
                'price' => 49000,
                'discount_price' => 34000,
                'description' => 'Tas bahu wanita berbahan kulit sintetis halus dengan aksen pita ribbon cantik. Kompartemen muat dompet, HP & alat kosmetik.',
                'image' => 'products/34.000 - 1.jpeg',
                'category_id' => $catModels[0]->id,
                'stock' => 20,
            ],
            [
                'name' => 'Tas Selempang Boston Casual',
                'price' => 49000,
                'discount_price' => 34000,
                'description' => 'Tas selempang wanita model boston bag kasual nan stylish, sangat cocok untuk menemani aktivitas harian Anda.',
                'image' => 'products/34.000.jpeg',
                'category_id' => $catModels[0]->id,
                'stock' => 20,
            ],
            [
                'name' => 'Dompet Lipat Monogram Clover',
                'price' => 52000,
                'discount_price' => 37000,
                'description' => 'Dompet lipat wanita dengan hiasan bros bunga warna emas nan mewah. Lengkap dengan slot uang kertas, koin, dan kartu.',
                'image' => 'products/37.000.jpeg',
                'category_id' => $catModels[1]->id,
                'stock' => 15,
            ],
            [
                'name' => 'Tas Handbag Grace Top Handle',
                'price' => 70000,
                'discount_price' => 55000,
                'description' => 'Tas tangan wanita seri Grace warna krem anggun, dilengkapi tali panjang dan gantungan liontin emas eksklusif.',
                'image' => 'products/55.000.jpeg',
                'category_id' => $catModels[2]->id,
                'stock' => 15,
            ],
            [
                'name' => 'Tas Handbag Grace Classic Black',
                'price' => 70000,
                'discount_price' => 55000,
                'description' => 'Tas tangan wanita seri Grace warna hitam elegan, sangat cocok untuk menghadiri acara pesta maupun hangout santai.',
                'image' => 'products/55.000 - 1.jpeg',
                'category_id' => $catModels[2]->id,
                'stock' => 15,
            ],
            [
                'name' => 'Tas Handbag Grace Heritage Brown',
                'price' => 70000,
                'discount_price' => 55000,
                'description' => 'Tas tangan wanita seri Grace warna cokelat classy berbahan kokoh dengan jahitan yang sangat rapi.',
                'image' => 'products/55.000 - 2.jpeg',
                'category_id' => $catModels[2]->id,
                'stock' => 15,
            ],
            [
                'name' => 'Tas Handbag Bowling Vintage Mocca',
                'price' => 75000,
                'discount_price' => 60000,
                'description' => 'Tas tote bag wanita eksklusif Vyora dengan kapasitas muat luas, tali selempang serbaguna, dan desain timeless.',
                'image' => 'products/60.000.jpeg',
                'category_id' => $catModels[2]->id,
                'stock' => 10,
            ],
            [
                'name' => 'Tas Handbag Bowling Vintage Black',
                'price' => 75000,
                'discount_price' => 60000,
                'description' => 'Tas selempang wanita gaya vintage klasik berbahan tebal pilihan yang awet dan fashionable.',
                'image' => 'products/60.000 - 1.jpeg',
                'category_id' => $catModels[2]->id,
                'stock' => 10,
            ],
            [
                'name' => 'Tas Handbag Bowling Vintage Coktu',
                'price' => 75000,
                'discount_price' => 60000,
                'description' => 'Tas selempang wanita favorit Vyora Store dengan kombinasi style cantik, elegan, dan simpel.',
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
