<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourierService extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql_pos';

    protected $fillable = [
        'courier_id', 'name', 'code'
    ];

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }
}
