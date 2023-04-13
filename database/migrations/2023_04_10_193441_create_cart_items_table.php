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
        Schema::create('shop_cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shop\Cart::class, 'shop_cart_id')
                ->constrained('shop_carts')
                ->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Shop\ProductVariant::class, 'shop_product_variant_id')
                ->constrained('shop_product_variants')
                ->cascadeOnDelete();
            $table->string('name');
            $table->decimal('unit_price');
            $table->integer('weight');
            $table->integer('quantity');
            $table->decimal('discount');
            $table->decimal('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_cart_items');
    }
};
