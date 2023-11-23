<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blog';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $fillable = array('blog_title', 'blog_slug', 'blog_content', 'blog_category', 'blog_tag', 'seo_meta_title', 'seo_meta_description', 'seo_meta_keyword', 'blog_image');
}
