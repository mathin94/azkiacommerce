<?php

namespace App\Http\Livewire\Blogs;

use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Show extends Component
{
    public Post | null $post;
    public Post | null $next_post;
    public Post | null $prev_post;
    public $categories;

    public function incrementView()
    {
        if (!auth()->check()) {
            $combination = (\Str::replace('.', '-', request()->ip()) . '::' . $this->post->id);
        } else {
            $combination = (auth()->id() . '::' . $this->post->id);
        }

        $cache_key = "post_view_count::{$combination}";

        $cache = Cache::get($cache_key);

        if (!$cache) {
            $this->post->increment('view_count');
            Cache::set($cache_key, 1, 10 * 60);
        }
    }

    public function mount()
    {
        $slug           = request()->route('slug');
        $cache_key      = Post::CACHE_PREFIX . $slug;
        $next_cache_key = $cache_key . '::next';
        $prev_cache_key = $cache_key . '::prev';

        $post = Cache::remember($cache_key, Post::DEFAULT_CACHE_TTL, function () use ($slug) {
            return Post::with(['media', 'author', 'comments', 'category', 'tags'])
                ->published()
                ->whereSlug($slug)
                ->first();
        });

        $this->post = $post;

        abort_if(empty($this->post), 404);

        $this->next_post = Cache::remember($next_cache_key, Post::DEFAULT_CACHE_TTL, function () use ($post) {
            return $post->nextPost();
        });

        $this->prev_post = Cache::remember($prev_cache_key, Post::DEFAULT_CACHE_TTL, function () use ($post) {
            return $post->prevPost();
        });

        $this->categories = Cache::remember('', 60 * 60 * 24, function () {
            return Category::whereActive(true)->get();
        });

        $this->incrementView();
    }

    public function render()
    {
        return view('livewire.blogs.show')
            ->layout('layouts.frontpage', [
                'title' => $this->post->title
            ]);
    }
}
