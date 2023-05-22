<?php

namespace App\Models\Backoffice;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courier extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql_pos';

    protected $fillable = [
        'name', 'code'
    ];

    public function services()
    {
        return $this->hasMany(CourierService::class);
    }

    public function scopeSupported(Builder $query): void
    {
        $codes = [
            'jne',
            'pos',
            'tiki',
            'wahana',
            'sicepat',
            'jnt',
            'jet',
            'ninja',
            'lion',
        ];

        $query->whereIn('code', $codes);
    }
}
