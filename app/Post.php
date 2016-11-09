<?php namespace App;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = ['title', 'description', 'cover', 'keywords', 'status'];

    protected $hidden = [];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function category()
    {
        return $this->belongsTo('App\PostCategory', 'id', 'post_id');
    }

    public function scopeSelectedCategory($query, $category_id)
    {
        if ($category_id) {
            $query->whereHas('category', function ($query) use ($category_id) {
                $query->where('parent_category_id', $category_id)->orWhere('category_id', $category_id);
            });
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