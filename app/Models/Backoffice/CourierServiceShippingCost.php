<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourierServiceShippingCost extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql_pos';

    protected $fillable = [
        'outlet_id',
        'courier_service_id',
        'province_id',
        'city_id',
        'subdistrict_id',
        'courier_service_code',
        'cost',
        'is_fixed',
        'active_at',
        'inactive_at',
        'entry_user_id',
        'edit_user_id',
        'delete_user_id'
    ];

    protected $casts = [
        'active_at' => 'datetime',
        'inactive_at' => 'datetime'
    ];

    public function courierService()
    {
        return $this->belongsTo(CourierService::class);
    }
}
