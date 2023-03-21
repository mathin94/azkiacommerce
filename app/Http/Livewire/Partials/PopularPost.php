<?php

namespace App\Http\Livewire\Partials;

use App\Models\Blog\Post;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class PopularPost extends Component
{
    public $posts;

    public function mount()
    {
        $this->posts = Cache::remember(Post::TOP_POPULAR_CACHE, 60 * 60, function () {
            return Post::getPopular()->limit(5)->get();
        });
    }

    public function render()
    {
        return view('livewire.partials.popular-post');
    }
}
