<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $connection = 'mysql_pos';

    protected $fillable = [
        'id', 'province_id', 'name', 'type'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function subdistricts()
    {
        return $this->hasMany(Subdistrict::class);
    }
}
