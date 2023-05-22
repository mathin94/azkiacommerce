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
        Schema::create('shop_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shop\Order::class, 'shop_order_id')
                ->constrained('shop_orders')
                ->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Shop\ProductVariant::class, 'shop_product_variant_id')
                ->constrained('shop_product_variants')
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('alternate_name')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->decimal('normal_price', 12, 3)->default(0);
            $table->decimal('price', 12, 3)->default(0);
            $table->integer('weight')->default(0);
            $table->integer('quantity')->default(0);
            $table->decimal('discount', 12, 3)->default(0);
            $table->decimal('total_price', 12, 3)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_order_items');
    }
};
