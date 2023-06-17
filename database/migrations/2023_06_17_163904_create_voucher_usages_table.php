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
        Schema::create('shop_voucher_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shop\Voucher::class, 'shop_voucher_id')
                ->constrained('shop_vouchers')
                ->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Shop\Order::class, 'shop_order_id')
                ->constrained('shop_orders')
                ->cascadeOnDelete();
            $table->decimal('amount', 13, 2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_voucher_usages');
    }
};
