<?php

namespace App\Http\Livewire\Pages;

use App\Models\Page;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Exception\NotFoundException;
use Livewire\Component;

class StaticPage extends Component
{
    public Page | null $page;

    public function mount()
    {
        $slug = request()->route('slug');

        $this->page = Cache::remember("page::{$slug}", 60 * 60 * 24, function () use ($slug) {
            $page = Page::whereSlug($slug)
                ->whereActive(true)
                ->first();

            if (!$page) {
                return;
            }

            $page->loadMedia('default')->reverse();

            return $page;
        });

        abort_if(!$this->page, 404);
    }

    public function render()
    {
        return view('livewire.pages.static-page')
            ->layout('layouts.frontpage', [
                'title' => $this->page->title
            ]);
    }
}
