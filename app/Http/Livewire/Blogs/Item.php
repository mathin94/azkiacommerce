<?php

namespace App\Http\Livewire\Blogs;

use Livewire\Component;
use App\Models\Blog\Post;
use Illuminate\Support\Facades\Cache;

class Item extends Component
{
    public $item;

    public function mount($item)
    {
        $slug           = $item->slug;
        $cache_key      = Post::CACHE_PREFIX . $slug;

        $this->item = Cache::remember($cache_key, Post::DEFAULT_CACHE_TTL, function () use ($item) {
            return $item->load(['media', 'author', 'comments', 'category', 'tags']);
        });
    }

    public function render()
    {
        return view('livewire.blogs.item');
    }
}
