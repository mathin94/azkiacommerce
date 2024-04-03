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
        Schema::table('shop_product_limitations', function (Blueprint $table) {
            $table->dropColumn('is_active');

            $table->timestamp('active_at')->nullable();
            $table->timestamp('inactive_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_product_limitations', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);

            $table->dropColumn('active_at');
            $table->dropColumn('inactive_at');
        });
    }
};
