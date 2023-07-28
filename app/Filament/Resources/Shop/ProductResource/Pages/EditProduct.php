<?php

namespace App\Filament\Resources\Shop\ProductResource\Pages;

use App\Filament\Resources\Shop\ProductResource;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament::resources.pages.edit-record-custom';

    protected function getActions(): array
    {
        return [
            Actions\Action::make('view')
                ->button()
                ->color('success')
                ->icon('heroicon-s-eye')
                ->url($this->record->public_url)
                ->label('Lihat Produk')
                ->extraAttributes(['target' => '_blank']),
            Actions\DeleteAction::make(),
        ];
    }
}
