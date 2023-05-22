<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $connection = 'mysql_pos';

    protected $fillable = [
        'id', 'name'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
