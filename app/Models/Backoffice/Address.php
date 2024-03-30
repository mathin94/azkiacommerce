<?php

namespace App\Models\Backoffice;

use App\Models\Shop\Customer as ShopCustomer;
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
        return $this->belongsTo(Customer::class);
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

    protected function statusBadge(): Attribute
    {
        return Attribute::make(get: function () {
            return $this->is_main ? "<span class='badge badge-success'>Utama</span>" : '';
        });
    }

    // scope search
    public function scopeSearch($query, $search)
    {
        return $query->where('label', 'like', "%$search%")
            ->orWhere('recipient_name', 'like', "%$search%")
            ->orWhere('recipient_phone', 'like', "%$search%")
            ->orWhere('address_line', 'like', "%$search%")
            ->orWhere('post_code', 'like', "%$search%")
            ->orWhereHas('subdistrict', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('city_name', 'like', "%$search%");
            });
    }

    // scope Order Default
    public function scopeOrderDefault($query)
    {
        return $query->orderBy('is_main', 'desc')
            ->orderBy('label', 'asc');
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            if ($model->isDirty('subdistrict_id') && $model->is_main === true) {
                $model->customer()
                    ->update(['subdistrict_id' => $model->subdistrict_id]);
            }

            if ($model->is_main === true) {
                Address::where('customer_id', $model->customer_id)
                    ->where('id', '!=', $model->id)
                    ->update(['is_main' => false]);
            }
        });
    }
}
