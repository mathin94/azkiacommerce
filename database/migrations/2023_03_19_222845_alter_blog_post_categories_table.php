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
        Schema::table('blog_post_categories', function (Blueprint $table) {
            $table->dropColumn('visibility');
            $table->boolean('active')->default(true);
            $table->integer('post_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_post_categories', function (Blueprint $table) {
            $table->dropColumn('active');
            $table->dropColumn('post_count');
            $table->boolean('visibility')->default(true);
        });
    }
};
