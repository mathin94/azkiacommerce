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
        Schema::create('shop_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shop\Customer::class, 'shop_customer_id')
                ->constrained('shop_customers')
                ->cascadeOnDelete();
            $table->integer('status');
            $table->decimal('total_weight', 12, 2, true)->default(0);
            $table->decimal('subtotal', 13, 2, true)->default(0);
            $table->decimal('shipping_cost', 13, 2, true)->default(0);
            $table->decimal('grandtotal', 13, 2, true)->default(0);
            $table->timestamp('checked_out_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_carts');
    }
};
