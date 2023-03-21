<?php

namespace Database\Factories\Blog;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence;

        $post = collect($this->faker->paragraphs(random_int(5, 15)))
            ->map(function ($item) {
                return "<p>$item</p>";
            })->toArray();

        $post = implode($post);

        return [
            'blog_post_category_id' => 13,
            'author_id'             => 1,
            'title'                 => $title,
            'slug'                  => \Str::slug($title),
            'content'               => $post,
            'published_at'          => now(),
            'tags' => [
                'Berita', 'Info', 'Tips dan Trik'
            ]
        ];
    }
}
