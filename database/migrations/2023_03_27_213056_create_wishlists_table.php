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
        Schema::create('shop_wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shop\Product::class, 'shop_product_id')
                ->constrained('shop_products')
                ->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Shop\Customer::class, 'shop_customer_id')
                ->constrained('shop_customers')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
