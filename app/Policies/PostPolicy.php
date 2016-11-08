<?php

namespace App\Policies;

use App\Post;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * PostPolicy constructor.
     */
    public function __construct()
    {
        //
    }

    public function managePost(User $user, Post $post)
    {
        return ($user->is_admin == 1 || $user->owns($post));
    }
}
