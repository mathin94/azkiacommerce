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
    public $categories, $comments, $newComment;

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

    public function postComment()
    {
        $this->validate([
            'newComment' => 'required',
        ]);

        $this->post->comments()->create([
            'shop_customer_id' => auth()->guard('shop')->id(),
            'comment' => $this->newComment,
        ]);

        $this->reset('newComment');

        $this->post->refresh();
    }

    public function mount()
    {
        $slug           = request()->route('slug');
        $cache_key      = Post::CACHE_PREFIX . $slug;

        $post = Post::with([
            'media',
            'author',
            'category',
            'tags',
            'comments.customer',
        ])
            ->published()
            ->whereSlug($slug)
            ->first();

        $this->post = $post;

        abort_if(empty($this->post), 404);

        $this->next_post = $post->nextPost();

        $this->prev_post = $post->prevPost();

        $this->categories = Category::whereActive(true)->get();

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
