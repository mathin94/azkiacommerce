<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class ProductVariantTemplate implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithEvents, WithTitle
{
    public function __construct(
        protected $records
    ) {
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->records;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Barcode',
            'Nama Variant',
            'Warna',
            'Ukuran',
            'Harga Jual',
        ];
    }

    public function map($data): array
    {
        return [
            $data->id,
            $data->barcode,
            $data->name,
            $data->color?->name,
            $data->size?->name,
            $data->price
        ];
    }

    public function registerEvents(): array
    {
        return [
            // handle by a closure.
            AfterSheet::class => function (AfterSheet $event) {

                // get layout counts (add 1 to rows for heading row)
                $column_count =  empty($this->records) ? 10000 : $this->records->count();
                $row_count = (empty($this->records) ? $column_count : $this->records->count()) + 1;

                // set dropdown column
                $dropdown_color = 'D';

                // set dropdown list for first data row
                $validation = $event->sheet->getCell("{$dropdown_color}2")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Warna Tidak Valid');
                $validation->setError('Warna Harus ada di sheet list warna.');
                $validation->setPromptTitle('Pilih Warna');
                $validation->setPrompt('Silahkan Pilih Warna yang tersedia.');
                $validation->setFormula1('\'List Warna\'!$B$2:$B$10000');

                // clone validation to remaining rows
                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$dropdown_color}{$i}")->setDataValidation(clone $validation);
                }

                // set columns to autosize
                for ($i = 1; $i <= $column_count; $i++) {
                    $column = Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }

                $dropdown_size  = 'E';

                // set dropdown list for first data row
                $validation = $event->sheet->getCell("{$dropdown_size}2")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Ukuran Tidak Valid');
                $validation->setError('Ukuran Harus ada di sheet list Ukuran.');
                $validation->setPromptTitle('Pilih Ukuran');
                $validation->setPrompt('Silahkan Pilih Ukuran yang tersedia.');
                $validation->setFormula1('\'List Ukuran\'!$B$2:$B$10000');

                // clone validation to remaining rows
                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$dropdown_size}{$i}")->setDataValidation(clone $validation);
                }

                // set columns to autosize
                for ($i = 1; $i <= $column_count; $i++) {
                    $column = Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }

    public function title(): string
    {
        return 'List Variant';
    }
}
