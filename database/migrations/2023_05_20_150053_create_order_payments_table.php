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
        Schema::create('shop_order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shop\Order::class, 'shop_order_id')
                ->constrained('shop_orders')
                ->cascadeOnDelete();
            $table->uuid();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->json('payment_properties')->nullable();
            $table->text('proof_of_payment')->nullable();
            $table->timestamp('proof_uploaded_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_order_payments');
    }
};
