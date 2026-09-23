<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('colors')->nullable()->after('image');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->string('session_id')->nullable()->index()->after('user_id');
            $table->string('color')->nullable()->after('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('colors');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->dropColumn(['session_id', 'color']);
        });
    }
};
