<x-filament::page :class="\Illuminate\Support\Arr::toCssClasses([
    'filament-resources-list-records-page',
    'filament-resources-' . str_replace('/', '-', $this->getResource()::getSlug()),
])">
    <livewire:admin.orders.top-filter />
    {{ $this->table }}
</x-filament::page>
