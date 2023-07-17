<?php

namespace App\Filament\Resources\FAQResource\Pages;

use App\Filament\Resources\FAQResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFAQS extends ManageRecords
{
    protected static string $resource = FAQResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
