<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql_pos';

    protected $fillable = [
        'password'
    ];

    public static function boot()
    {
        parent::boot();

        /**
         * Prevent the model from being deleted.
         *
         * @param  mixed  $model
         * @return bool
         */

        static::deleting(function ($model) {
            return false;
        });
    }
}
