<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use Spatie\Tags\HasTags;

class Post extends Model
{
    use SoftDeletes, HasTags, HasSEO;

    protected $table = 'blog_posts';

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'content',
        'published_at',
        'image',
    ];

    protected $casts = [
        'published_at' => 'date'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(\App\Models\User::class, 'author_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->author_id = auth()->id();
        });
    }
}
