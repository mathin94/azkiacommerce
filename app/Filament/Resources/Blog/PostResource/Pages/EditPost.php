<?php

namespace App\Filament\Resources\Blog\PostResource\Pages;

use App\Filament\Resources\Blog\PostResource;
use App\Models\Blog\Post;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function afterSave()
    {
        $category             = $this->record->category;
        $category->post_count = $category->posts->count();
        $category->save();

        $cache_key = Post::CACHE_PREFIX . $this->record->slug;

        Cache::forget($cache_key);
        Cache::forget(Post::TOP_CACHE_PREFIX);
        Cache::forget(Post::TOP_POPULAR_CACHE);
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
