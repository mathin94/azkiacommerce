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
        Schema::create('shop_product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->foreignIdFor(\App\Models\Shop\Product::class, 'shop_product_id')
                ->constrained('shop_products')
                ->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Color::class)
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Size::class)
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->string('barcode');
            $table->string('code_name');
            $table->string('name');
            $table->integer('weight');
            $table->integer('price')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_product_variants');
    }
};
