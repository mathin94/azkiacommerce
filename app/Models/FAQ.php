<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FAQ extends Model
{
    use SoftDeletes;

    protected $table = 'faqs';

    protected $fillable = [
        'question', 'answer', 'index'
    ];
}
