<?php

namespace App\Models\Shop;

use App\Enums\CartStatus;
use App\Enums\GenderEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Rennokki\QueryCache\Traits\QueryCacheable;
use App\Models\Backoffice\Address as ShippingAddress;
use App\Models\Backoffice\CustomerType;

class Customer extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable, QueryCacheable;

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
    public $cacheTags = ['shop_customers'];

    /**
     * A cache prefix string that will be prefixed
     * on each cache key generation.
     *
     * @var string
     */
    public $cachePrefix = 'shop_customers_';

    /**
     * Invalidate the cache automatically
     * upon update in the database.
     *
     * @var bool
     */
    protected static $flushCacheOnUpdate = true;

    /**
     * The cache driver to be used.
     *
     * @var string
     */
    public $cacheDriver = 'redis';

    protected $table = 'shop_customers';

    protected $fillable = [
        'resource_id',
        'code',
        'name',
        'store_name',
        'latitude',
        'longitude',
        'phone',
        'email',
        'password',
        'email_verified_at',
        'remember_token',
        'authorization_token',
        'gender',
        'avatar',
        'is_active',
        'is_branch',
        'is_default_password',
        'created_at',
        'customer_type_id',
        'customer_type_json',
    ];

    protected $casts = [
        'is_active'           => 'boolean',
        'is_branch'           => 'boolean',
        'is_default_password' => 'boolean',
        'customer_type'       => 'json',
        'gender' => GenderEnum::class,
    ];

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'shop_customer_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'shop_wishlists', 'shop_customer_id', 'shop_product_id')
            ->withTimestamps();
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'shop_customer_id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'shop_customer_id')
            ->where('status', CartStatus::Draft);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'shop_customer_id');
    }

    public function addresses()
    {
        return $this->hasMany(ShippingAddress::class, 'customer_id', 'resource_id');
    }

    public function mainAddress()
    {
        return $this->hasOne(ShippingAddress::class, 'customer_id', 'resource_id')->whereIsMain(true);
    }

    protected function fullMainAddress(): Attribute
    {
        return Attribute::make(get: fn () => $this->mainAddress->full_address);
    }

    public function customerType()
    {
        return $this->belongsTo(CustomerType::class);
    }

    public function resource()
    {
        return $this->belongsTo(\App\Models\Backoffice\Customer::class, 'resource_id');
    }

    /**
     * Boot the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
    }
}
