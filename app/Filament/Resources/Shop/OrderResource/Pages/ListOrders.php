<?php

namespace App\Filament\Resources\Shop\OrderResource\Pages;

use App\Filament\Resources\Shop\OrderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected static string $view = 'filament::resources.customs.orders.list-records';

    protected $queryString = [
        'tableFilters' => ['as' => 'filters'],
        'tableSearchQuery' => ['except' => '', 'as' => 'search'],
    ];

    protected $listeners = ['setStatus' => 'handleStatus'];

    public function handleStatus(string $status)
    {
        $this->tableFilters['status']['value'] = $status;
    }

    public function updatedTableFilters(): void
    {
        $status = $this->tableFilters['status']['value'];

        $this->emit('setStatus', $status);
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
