<?php

namespace App\Imports;

use App\Models\Color;
use App\Models\Shop\Product;
use App\Models\Shop\ProductVariant;
use App\Models\Size;
use App\Models\UploadExcelLog;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithColumnLimit;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Ramsey\Uuid\Uuid;

class UpdateMassalProductVariant implements ToCollection, WithStartRow, WithChunkReading, SkipsEmptyRows, WithColumnLimit, ShouldQueue
{
    use Importable;

    protected $log;
    protected $colors;
    protected $sizes;
    protected $product;

    public function __construct($product)
    {
        $uuid = Uuid::uuid4();
        $cache_ttl = now()->addHours(24)->diffInSeconds();
        $this->log = UploadExcelLog::firstOrCreate(['batch_id' => $uuid]);

        $this->colors = Cache::remember(Color::ALL_CACHE_KEY, $cache_ttl, function () {
            return Color::orderBy('name')->get();
        });

        $this->sizes = Cache::remember(Size::ALL_CACHE_KEY, $cache_ttl, function () {
            return Size::orderBy('name')->get();
        });

        $this->product = $product;
    }
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            if ($row->filter()->isNotEmpty()) {
                $this->log->increment('process_count');

                $params = [
                    'id'      => $row[0],
                    'barcode' => $row[1],
                    'name'    => $row[2],
                    'color'   => $row[3],
                    'size'    => $row[4],
                    'price'   => $row[5],
                    'image'   => $row[6],
                ];

                $color = $this->colors->where('name', $params['color'])->first();

                if (!$color) {
                    $color = Color::create([
                        'name' => $params['color'],
                        'code' => \Str::slug($params['color'])
                    ]);
                }

                $size = $this->sizes->where('name', $params['size'])->first();

                if (!$size) {
                    $size = Size::create([
                        'name' => $params['size'],
                        'code' => \Str::slug($params['size'])
                    ]);
                }

                $variant = ProductVariant::find($params['id']);

                if (!$variant) {
                    $this->log->addMessage([
                        'variant_id' => $params['id'],
                        'barcode'    => $params['barcode'],
                        'message'    => "Data Tidak Ditemukan"
                    ]);

                    continue;
                }

                $variant_data = [
                    'color_id' => $color->id,
                    'size_id'  => $size->id,
                    'price'    => $params['price']
                ];

                $media = $this->product->getMedia(Product::GALLERY_IMAGE_COLLECTION_NAME)
                    ->where('file_name', $params['image'])
                    ->first();

                if ($media) {
                    $variant_data['media_id'] = $media->id;
                }

                $variant->update($variant_data);
            }
        }
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return env('BATCH_UPLOAD_VARIANT_CHUNK_SIZE', 200);
    }

    public function endColumn(): String
    {
        return 'G';
    }

    public function isEmptyWhen(array $row): bool
    {
        return empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3]) || empty($row[4]) || empty($row[5]);
    }
}
