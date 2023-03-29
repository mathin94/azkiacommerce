<?php

namespace App\Models\Shop;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Authenticatable
{
    use HasFactory, SoftDeletes, Cachable;

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
    ];

    protected $casts = [
        'is_active'           => 'boolean',
        'is_branch'           => 'boolean',
        'is_default_password' => 'boolean',
    ];

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            Wishlist::class,
            'shop_customer_id',
            'id',
            'id',
            'shop_product_id'
        );
    }
}
