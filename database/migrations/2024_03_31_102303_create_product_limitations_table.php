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
        Schema::create('shop_product_limitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_product_id')->constrained();
            $table->unsignedBigInteger('customer_type_id');
            $table->integer('quantity_limit');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_product_limitations');
    }
};
