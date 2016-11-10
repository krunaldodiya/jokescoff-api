<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = ['id', 'title', 'description', 'parent_category_id', 'category_id', 'cover', 'status'];

    protected $hidden = [];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }

    public function scopeSelectedCategory($query, $category_id)
    {
        if ($category_id) {
            return $query->where('posts.parent_category_id', $category_id)->orWhere('posts.category_id', $category_id);
        }

        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('posts.title', 'LIKE', "%$search%")->orWhere('posts.description', 'LIKE', "%$search%");
        }

        return $query;
    }
}