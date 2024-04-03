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
        'active_at',
        'inactive_at',
    ];

    protected $casts = [
        'active_at' => 'datetime',
        'inactive_at' => 'datetime',
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

    /**
     * Scope Active
     */

    public function scopeActive($query)
    {
        return $query->where('active_at', '<=', now())
            ->where('inactive_at', '>', now());
    }

    /**
     * scope notExpired
     */

    public function scopeNotExpired($query)
    {
        return $query->where('inactive_at', '>', now());
    }

    /**
     * scope expired
     */

    public function scopeExpired($query)
    {
        return $query->where('inactive_at', '<=', now());
    }
}
