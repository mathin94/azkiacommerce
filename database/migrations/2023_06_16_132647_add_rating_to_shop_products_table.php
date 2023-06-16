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
        Schema::table('shop_products', function (Blueprint $table) {
            $table->decimal('rating', 12, 2, true)->after('order_count')->default(0);
            $table->integer('review_count')->after('rating')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->dropColumn('rating');
            $table->dropColumn('review_count');
        });
    }
};
