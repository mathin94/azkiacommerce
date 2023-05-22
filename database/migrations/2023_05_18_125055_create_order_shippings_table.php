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
        Schema::create('shop_order_shippings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shop\Order::class, 'shop_order_id')
                ->constrained('shop_orders')
                ->cascadeOnDelete();
            $table->unsignedBigInteger('subdistrict_id')->nullable();
            $table->unsignedBigInteger('courier_id')->nullable();
            $table->string('courier_service');
            $table->json('courier_properties')->nullable();
            $table->string('receipt_number')->nullable();
            $table->boolean('is_dropship')->default(false);
            $table->string('dropshipper_name')->nullable();
            $table->string('dropshipper_phone')->nullable();
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->string('recipient_address');
            $table->decimal('shipping_cost_estimation', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_order_shippings');
    }
};
