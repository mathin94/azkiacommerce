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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->integer('value_type');
            $table->integer('voucher_type');
            $table->string('code');
            $table->decimal('minimum_order')->default(0);
            $table->decimal('maximum_discount')->nullable();
            $table->integer('quota');
            $table->decimal('value');
            $table->timestamp('active_at');
            $table->timestamp('inactive_at');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
