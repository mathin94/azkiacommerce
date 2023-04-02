<?php

namespace App\Filament\Resources\Shop\VoucherResource\Pages;

use App\Filament\Resources\Shop\VoucherResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageVouchers extends ManageRecords
{
    protected static string $resource = VoucherResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
