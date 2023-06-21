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
        Schema::table('shop_order_shippings', function (Blueprint $table) {
            $table->json('tracking_details')->nullable();
            $table->datetime('tracking_updated_at')->nullable();
            $table->datetime('delivered_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_order_shippings', function (Blueprint $table) {
            $table->dropColumn('tracking_details');
            $table->dropColumn('tracking_updated_at');
            $table->dropColumn('delivered_at');
        });
    }
};
