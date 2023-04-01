<?php

namespace App\Exports\Sheets;

use App\Models\Color;
use App\Models\Shop\Product;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductImageList implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithTitle
{
    protected $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->product->getMedia(Product::GALLERY_IMAGE_COLLECTION_NAME);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama File',
        ];
    }

    public function map($data): array
    {
        return [
            $data->id,
            $data->file_name
        ];
    }

    public function title(): string
    {
        return 'List Foto Produk';
    }
}
