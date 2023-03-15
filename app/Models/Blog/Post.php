<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use Spatie\Tags\HasTags;

class Post extends Model
{
    use SoftDeletes, HasTags, HasSEO, HasFactory;

    protected $table = 'blog_posts';

    protected $fillable = [
        'blog_post_category_id',
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
        return $this->belongsTo(Category::class, 'blog_post_category_id');
    }

    public function author()
    {
        return $this->belongsTo(\App\Models\User::class, 'author_id');
    }

    public function comments()
    {
        return $this->morphMany(\App\Models\Comment::class, 'commentable');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->author_id = auth()->id();
        });
    }
}
