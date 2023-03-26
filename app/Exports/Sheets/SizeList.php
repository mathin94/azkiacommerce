<?php

namespace App\Exports\Sheets;

use App\Models\Size;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SizeList implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $ttl = now()->addHours(24)->diffInSeconds();

        return Cache::remember(Size::ALL_CACHE_KEY, $ttl, function () {
            return Size::orderBy('name')->get();
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Ukuran',
        ];
    }

    public function map($data): array
    {
        return [
            $data->id,
            $data->name
        ];
    }

    public function title(): string
    {
        return 'List Ukuran';
    }
}
