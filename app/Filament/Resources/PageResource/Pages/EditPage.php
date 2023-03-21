<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use App\Models\Page;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function afterSave()
    {
        $cache_key = Page::CACHE_KEY_PREFIX . $this->record->slug;
        Cache::forget($cache_key);
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
