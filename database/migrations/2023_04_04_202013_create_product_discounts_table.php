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
        Schema::create('shop_product_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shop\Product::class, 'shop_product_id')
                ->constrained('shop_products')
                ->cascadeOnDelete();
            $table->integer('discount_type');
            $table->boolean('with_membership_price')->default(false);
            $table->integer('discount_percentage');
            $table->integer('maximum_qty')->nullable();
            $table->timestamp('active_at')->nullable();
            $table->timestamp('inactive_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_discounts');
    }
};
