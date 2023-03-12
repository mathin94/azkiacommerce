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
        Schema::create('shop_customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id')->nullable();
            $table->string('code')->index();
            $table->string('name');
            $table->string('store_name')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->index();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->string('auth_token')->nullable();
            $table->string('gender')->nullable();
            $table->boolean('is_active')->default(0);
            $table->boolean('is_branch')->default(0);
            $table->boolean('is_default_password')->default(0);
            $table->string('avatar')->nullable();
            $table->softDeletes()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_customers');
    }
};
