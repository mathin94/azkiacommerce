<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    protected $connection = 'mysql_pos';

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }
}
