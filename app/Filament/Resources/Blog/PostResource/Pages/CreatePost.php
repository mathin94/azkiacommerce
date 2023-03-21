<?php

namespace App\Filament\Resources\Blog\PostResource\Pages;

use App\Models\Blog\Post;
use Illuminate\Support\Facades\Cache;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Blog\PostResource;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function afterSave()
    {
        $category             = $this->record->category;
        $category->post_count = $category->posts->count();
        $category->save();

        Cache::forget(Post::TOP_CACHE_PREFIX);
    }
}
