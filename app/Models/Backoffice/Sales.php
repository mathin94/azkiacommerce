<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql_pos';

    public function details()
    {
        return $this->hasMany(SalesDetail::class);
    }
}
