<?php

namespace App\Filament\Resources\Shop\ProductDiscountResource\Pages;

use App\Filament\Resources\Shop\ProductDiscountResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductDiscounts extends ListRecords
{
    protected static string $resource = ProductDiscountResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
