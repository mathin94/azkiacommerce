<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql_pos';

    protected $fillable = [
        'id',
        'customer_id',
        'subdistrict_id',
        'label',
        'recipient_name',
        'recipient_phone',
        'address_line',
        'post_code',
        'is_main'
    ];

    public function customer()
    {
        return $this->belongsTo(\App\Models\Shop\Customer::class, 'customer_id', 'resource_id');
    }

    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class);
    }

    protected function fullAddress(): Attribute
    {
        return Attribute::make(get: function () {
            $address = "$this->address_line, {$this->subdistrict->name}, {$this->subdistrict->city_name}, {$this->subdistrict->province_name}";

            if (!is_null($this->post_code)) {
                $address .= " - $this->post_code";
            }

            return $address;
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            if ($model->is_main) {
                $model->customer()
                    ->update(['subdistrict_id' => $model->subdistrict_id]);

                $model->customer->addresses()
                    ->where('id', '!=', $model->id)
                    ->update(['is_main' => false]);
            }
        });

        static::updating(function ($model) {
            if ($model->is_main) {
                $model->customer()
                    ->update(['subdistrict_id' => $model->subdistrict_id]);

                $model->customer->addresses()
                    ->where('id', '!=', $model->id)
                    ->update(['is_main' => false]);
            }
        });
    }
}
