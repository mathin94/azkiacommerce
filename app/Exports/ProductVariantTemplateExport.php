<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\ColorList;
use App\Exports\Sheets\SizeList;
use App\Exports\Sheets\ProductVariantTemplate;

class ProductVariantTemplateExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(
        protected $records // variants data
    ) {
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            new ProductVariantTemplate($this->records),
            new ColorList(),
            new SizeList(),
        ];
    }
}
