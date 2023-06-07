<?php

namespace App\Http\Livewire\Home;

use Livewire\Component;
use App\Models\Blog\Post;
use Illuminate\Support\Facades\Cache;

class LatestPost extends Component
{
    public $posts;

    public function mount()
    {
        $this->posts = Post::cacheTags(['posts_latest_'])
            ->with('media')
            ->where('published_at', '<=', now())
            ->with('category', 'comments')
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.home.latest-post');
    }
}
