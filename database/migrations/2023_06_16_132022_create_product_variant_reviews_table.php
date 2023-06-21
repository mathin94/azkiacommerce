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
        Schema::create('shop_product_variant_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shop\OrderItem::class, 'shop_order_item_id')
                ->constrained('shop_order_items')
                ->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Shop\ProductVariant::class, 'shop_product_variant_id')
                ->constrained('shop_product_variants')
                ->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Shop\Customer::class, 'shop_customer_id')
                ->constrained('shop_customers')
                ->cascadeOnDelete();
            $table->integer('rating');
            $table->text('review');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_product_variant_reviews');
    }
};
