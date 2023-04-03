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
            $table->integer('minimum_order')->default(0);
            $table->integer('maximum_discount')->nullable();
            $table->integer('quota');
            $table->integer('value');
            $table->timestamp('active_at')->nullable();
            $table->timestamp('inactive_at')->nullable();
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
