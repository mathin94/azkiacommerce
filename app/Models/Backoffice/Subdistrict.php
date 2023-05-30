<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    protected $connection = 'mysql_pos';

    protected $fillable = [
        'id', 'city_id', 'name', 'city_type', 'city_name', 'province_name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    protected function nameLabel(): Attribute
    {
        return Attribute::make(get: function () {
            return "$this->name - $this->city_type $this->city_name - $this->province_name";
        });
    }
}
