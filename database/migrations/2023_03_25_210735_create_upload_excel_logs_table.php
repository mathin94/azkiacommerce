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
        Schema::create('upload_excel_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('batch_id');
            $table->json('messages')->nullable();
            $table->integer('error_count')->default(0);
            $table->integer('process_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_excel_logs');
    }
};
