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
        Schema::create('shop_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shop\Customer::class, 'shop_customer_id')
                ->constrained('shop_customers')
                ->cascadeOnDelete();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('packing_employee_id')->nullable();
            $table->unsignedBigInteger('resource_id')->nullable();
            $table->string('number');
            $table->string('invoice_number');
            $table->decimal('total_weight', 12, 3)->default(0);
            $table->decimal('total', 12, 3)->default(0);
            $table->decimal('discount_voucher', 12, 3)->default(0);
            $table->decimal('shipping_cost', 12, 3)->default(0);
            $table->decimal('grandtotal', 12, 3)->default(0);
            $table->decimal('total_profit', 12, 3)->default(0);
            $table->integer('status')->index();
            $table->text('notes')->nullable();
            $table->string('prepared_by')->nullable();
            $table->string('approved_by')->nullable();
            $table->json('payment_properties')->nullable();
            $table->text('proof_of_payment')->nullable();
            $table->timestamp('proof_uploaded_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('canceled_at')->nullable()->index();
            $table->softDeletes()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_orders');
    }
};
