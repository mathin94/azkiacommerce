<?php

namespace App\Models\Shop;

use App\Models\Backoffice\Courier;
use App\Models\Backoffice\Subdistrict;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderShipping extends Model
{
    use SoftDeletes;

    protected $table = 'shop_order_shippings';

    protected $fillable = [
        'shop_order_id',
        'subdistrict_id',
        'courier_id',
        'courier_service',
        'courier_properties',
        'receipt_number',
        'is_dropship',
        'dropshipper_name',
        'dropshipper_phone',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'shipping_cost_estimation',
        'shipping_cost',
    ];

    protected $casts = [
        'is_dropship' => 'boolean',
        'courier_properties' => 'json',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'shop_order_id');
    }

    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class, 'subdistrict_id');
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'courier_id');
    }

    protected function fullAddress(): Attribute
    {
        return Attribute::make(get: function () {
            return "$this->recipient_address, Kecamatan {$this->subdistrict->name}, {$this->subdistrict->city_name}, {$this->subdistrict->province_name}";
        });
    }

    protected function courierLabel(): Attribute
    {
        return Attribute::make(get: function () {
            $prop = json_decode($this->courier_properties['value']);
            return "{$this->courier->name} - {$prop->description} ({$this->courier_properties['name']})";
        });
    }

    protected function shippingCostLabel(): Attribute
    {
        return Attribute::make(get: function () {
            return 'Rp. ' . number_format($this->shipping_cost_estimation, 0, ',', '.');
        });
    }

    protected function courierLabelAlternative(): Attribute
    {
        return Attribute::make(get: function () {
            $prop = json_decode($this->courier_properties['value']);
            $name = strtoupper($this->courier->code);
            return "{$name} - {$prop->description} ({$this->courier_properties['name']})";
        });
    }
}
