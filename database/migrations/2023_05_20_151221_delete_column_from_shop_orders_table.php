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
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->dropColumn('payment_properties');
            $table->dropColumn('proof_of_payment');
            $table->dropColumn('proof_uploaded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->json('payment_properties')->nullable()->after('approved_by');
            $table->text('proof_of_payment')->nullable()->after('payment_properties');
            $table->timestamp('proof_uploaded_at')->nullable()->after('proof_of_payment');
        });
    }
};
