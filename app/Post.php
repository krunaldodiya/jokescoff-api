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
        $selected_category = $category_id ? Category::find($category_id) : null;

        if (count($selected_category)) {
            $query->whereHas('category', function ($query) use ($selected_category) {
                $query->where('parent_category_id', ($selected_category->parent_id == 0) ? $selected_category->id : $selected_category->parent_id);

                if ($selected_category->parent_id != 0) {
                    $query->where('category_id', $selected_category->id);
                }
            });
        }

        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('posts.keywords', 'LIKE', "%$search%");
        }

        return $query;
    }
}