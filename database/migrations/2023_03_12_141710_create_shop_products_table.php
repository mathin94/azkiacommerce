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
        Schema::create('shop_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id')->nullable();
            $table->foreignIdFor(\App\Models\Shop\Category::class, 'shop_product_category_id')
                ->constrained('shop_product_categories')
                ->onDelete('cascade');
            $table->string('name')->index();
            $table->string('slug')->index();
            $table->longText('description');
            $table->string('seo_title', 60)->nullable();
            $table->longText('seo_description', 160)->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('visible')->default(true);
            $table->boolean('allow_preorder')->default(false);
            $table->softDeletes()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_products');
    }
};
