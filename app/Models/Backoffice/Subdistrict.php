<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    protected $connection = 'mysql_pos';

    protected $fillable = [
        'id', 'city_id', 'name'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
