<?php

namespace App\Models\Backoffice;

use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Product extends Model
{
    use HasFactory, SoftDeletes, QueryCacheable;

    /**
     * Specify the amount of time to cache queries.
     * Do not specify or set it to null to disable caching.
     *
     * @var int|\DateTime
     */
    public $cacheFor = 60 * 60 * 24; // 1 day

    /**
     * The tags for the query cache. Can be useful
     * if flushing cache for specific tags only.
     *
     * @var null|array
     */
    public $cacheTags = ['backoffice_products'];

    /**
     * A cache prefix string that will be prefixed
     * on each cache key generation.
     *
     * @var string
     */
    public $cachePrefix = 'backoffice_products_';

    /**
     * The cache driver to be used.
     *
     * @var string
     */
    public $cacheDriver = 'redis';

    protected $connection = 'mysql_pos';

    public function detail()
    {
        return $this->hasOne(ProductOutlet::class)
            ->where('product_outlets.outlet_id', 1);
    }

    protected function price(): Attribute
    {
        return Attribute::make(get: fn () => $this->detail?->retail_price);
    }

    protected function stock(): Attribute
    {
        return Attribute::make(get: fn () => $this->detail?->stock);
    }

    protected function stockLabel(): Attribute
    {
        return Attribute::make(get: function () {
            $stock = $this->stock;

            if ($stock > 5) {
                return 'Stok Tersedia';
            }

            if ($stock > 0) {
                return 'Stok Hampir Habis';
            }

            return 'Stok Tidak Tersedia';
        });
    }

    public function getFinalPrice($qty = 1)
    {
        if (auth()->guard('shop')->guest()) {
            return $this->price;
        }

        $response = Http::baseUrl(config('app.backoffice_api_url'))
            ->withOptions([
                'verify' => false,
            ])
            ->withToken(auth()->guard('shop')->user()->authorization_token)
            ->post("/customer/products/{$this->detail->id}/price", [
                'qty' => $qty
            ]);

        if ($response->ok()) {
            return $response->json('price');
        }

        return $this->price;
    }

    protected function productOutletId(): Attribute
    {
        return Attribute::make(get: fn () => $this->detail->id);
    }
}
