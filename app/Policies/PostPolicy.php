<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{

    public function view(User $user, Post $post): bool
    {
        return $post->user_id == $user->id;
    }

    public function update(User $user, Post $post): bool
    {
        return $post->user_id == $user->id;
    }

}
