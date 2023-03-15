<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'blog_post_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'visibility',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'blog_post_category_id');
    }
}
