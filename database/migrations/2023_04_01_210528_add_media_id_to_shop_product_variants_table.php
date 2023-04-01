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
        Schema::table('shop_product_variants', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Media::class, 'media_id')
                ->nullable()
                ->constrained('media')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_product_variants', function (Blueprint $table) {
            $table->dropColumn('media_id');
        });
    }
};
