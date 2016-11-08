<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorites extends Model
{
    protected $table = 'favorites';

    protected $fillable = ['user_id', 'post_id'];

    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
