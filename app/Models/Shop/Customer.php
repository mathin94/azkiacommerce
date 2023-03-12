<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'shop_customers';

    protected $fillable = [
        'external_id',
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
        'auth_token',
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
}
