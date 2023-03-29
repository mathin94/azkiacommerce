<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory, Cachable;

    protected $table = 'comments';

    protected $guards = [];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
