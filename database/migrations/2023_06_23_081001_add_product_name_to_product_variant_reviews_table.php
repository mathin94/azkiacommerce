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
        Schema::table('shop_product_variant_reviews', function (Blueprint $table) {
            $table->string('product_name')
                ->after('review')
                ->nullable();
            $table->string('variant_name')
                ->after('review')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_product_variant_reviews', function (Blueprint $table) {
            $table->dropColumn('product_name');
            $table->dropColumn('variant_name');
        });
    }
};
