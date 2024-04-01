<?php

namespace App\Models\Shop;

use App\Enums\Connection;
use App\Models\Backoffice\CustomerType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductLimitation extends Model
{
    use SoftDeletes;

    protected $connection = Connection::Ecommerce;

    protected $table = 'shop_product_limitations';

    protected $fillable = [
        'shop_product_id',
        'customer_type_id',
        'quantity_limit',
        'is_active',
    ];

    /**
     * Get the product that owns the ProductLimitation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'shop_product_id');
    }

    /**
     * Get the customerType that owns the ProductLimitation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customerType(): BelongsTo
    {
        return $this->setConnection(Connection::Backoffice)
            ->belongsTo(CustomerType::class, 'customer_type_id');
    }
}
