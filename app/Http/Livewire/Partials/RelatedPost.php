<?php

namespace App\Http\Livewire\Partials;

use App\Models\Blog\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class RelatedPost extends Component
{
    public Post $post;
    public $posts;

    public function mount(Post $post)
    {
        $post      = $this->post;
        $cache_key = Post::CACHE_PREFIX . $post->slug . '::related';
        $cache_ttl = 3 * 60 * 60; // 3 hours

        $this->posts = Cache::remember($cache_key, $cache_ttl, function () use ($post) {
            return $post->getRelated();
        });
    }

    public function render()
    {
        return view('livewire.partials.related-post');
    }
}
