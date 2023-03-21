<?php

namespace App\Http\Livewire\Blogs;

use Livewire\Component;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Illuminate\Support\Facades\Cache;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $categories,
        $category,
        $tags,
        $search;

    protected $queryString = ['search', 'category', 'tags'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->categories = Cache::remember(Category::ACTIVE_CACHE_KEY, 60 * 60 * 24, function () {
            return Category::whereActive(true)->get();
        });
    }

    public function render()
    {
        $posts = Post::published()
            ->with(['category', 'comments', 'media']);

        if ($this->search) {
            $posts->where('title', 'like', "%{$this->search}%");
        }

        if ($this->category) {
            $category_cache = Category::CACHE_PREFIX . $this->category;

            $category = Cache::remember($category_cache, 24 * 60 * 60, function () {
                return Category::whereSlug($this->category)->first();
            });

            if ($category) {
                $posts->where('blog_post_category_id', $category->id);
            }
        }

        if ($this->tags) {
            $posts->withAnyTags([$this->tags]);
        }

        return view('livewire.blogs.index', [
            'posts' => $posts->paginate(6)
        ]);
    }
}
