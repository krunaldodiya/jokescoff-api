<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $table = 'post_category';

    protected $fillable = ['post_id', 'parent_category_id', 'category_id', 'has_price'];
}