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
        Schema::table('shop_customers', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_type_id')->after('id')->nullable()->index();
            $table->json('customer_type')->after('customer_type_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_customers', function (Blueprint $table) {
            $table->dropColumn('customer_type_id');
            $table->dropColumn('customer_type');
        });
    }
};
