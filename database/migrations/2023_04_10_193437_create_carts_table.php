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
            $table->unsignedBigInteger('shipping_address_id')->nullable();
            $table->foreignIdFor(\App\Models\Shop\Customer::class, 'shop_customer_id')
                ->constrained('shop_customers')
                ->cascadeOnDelete();
            $table->string('number')->unique();
            $table->integer('status');
            $table->decimal('total_weight')->default(0);
            $table->decimal('subtotal')->default(0);
            $table->decimal('shipping_cost')->default(0);
            $table->decimal('total_price')->default(0);
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
