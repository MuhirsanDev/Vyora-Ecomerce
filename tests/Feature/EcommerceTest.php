<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcommerceTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_can_be_accessed(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('VYORA');
    }

    public function test_customer_can_register_and_login(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Customer',
            'email' => 'john@example.com',
            'phone' => '08123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
    }

    public function test_user_can_update_own_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'role' => 'customer',
        ]);

        $response = $this->actingAs($user)->post('/profile', [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'phone' => '08999999999',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
            'phone' => '08999999999',
        ]);
    }

    public function test_admin_can_block_and_unblock_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer', 'is_blocked' => false]);

        $response = $this->actingAs($admin)->patch(route('admin.users.toggle-block', $customer->id));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'is_blocked' => true,
        ]);
    }

    public function test_blocked_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'email' => 'blocked@example.com',
            'password' => bcrypt('password123'),
            'is_blocked' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'blocked@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_customer_can_add_product_to_cart(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $category = Category::create(['name' => 'Dress', 'slug' => 'dress']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Silk Dress',
            'slug' => 'silk-dress',
            'price' => 150000,
            'stock' => 10,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_non_admin_cannot_access_admin_panel(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_admin_panel_and_create_product(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Baju', 'slug' => 'baju']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);

        $createResponse = $this->actingAs($admin)->post('/admin/products', [
            'name' => 'Kemeja Formal',
            'category_id' => $category->id,
            'description' => 'Kemeja katun premium',
            'price' => 200000,
            'discount_price' => 180000,
            'stock' => 15,
            'is_active' => 1,
        ]);

        $createResponse->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Kemeja Formal',
            'price' => 200000,
        ]);
    }

    public function test_admin_can_manage_financial_transactions_and_download_xlsx(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Create transaction with Belum Bayar status
        $response = $this->actingAs($admin)->post('/admin/financial', [
            'customer_name' => 'Siti Rahma',
            'customer_phone' => '08123456789',
            'product_name' => 'Blouse Silk Elegant',
            'quantity' => 2,
            'price_per_unit' => 150000,
            'payment_method' => 'Transfer',
            'status' => 'Belum Bayar',
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('admin.financial.index'));
        $this->assertDatabaseHas('transactions', [
            'customer_name' => 'Siti Rahma',
            'status' => 'Belum Bayar',
            'total_amount' => 300000,
        ]);

        $trx = \App\Models\Transaction::where('customer_name', 'Siti Rahma')->first();

        // Update status to Lunas
        $updateResponse = $this->actingAs($admin)->patch(route('admin.financial.update-status', $trx->id), [
            'status' => 'Lunas',
        ]);
        $updateResponse->assertSessionHas('success');
        $this->assertDatabaseHas('transactions', [
            'id' => $trx->id,
            'status' => 'Lunas',
        ]);

        // Download Excel XLSX report
        $downloadResponse = $this->actingAs($admin)->get(route('admin.financial.download'));
        $downloadResponse->assertStatus(200);
        $downloadResponse->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
