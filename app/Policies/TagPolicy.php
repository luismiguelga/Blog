<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tag;

class TagPolicy
{
    public function update(User $user, Tag $tag): bool
    {
        return $tag->user_id == $user->id;;
    }
}
