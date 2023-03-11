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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('category_id');
            $table->string('slug');
            $table->string('title');
            $table->text('content');
            $table->string('image')->nullable();
            $table->timestamp('published_at');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
                ->on('blog_post_categories')
                ->onDelete('cascade');

            $table->foreign('author_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
