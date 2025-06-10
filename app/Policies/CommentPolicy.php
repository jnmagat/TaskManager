<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Comment $comment)
    {
        // only the author can edit
        return $user->id === $comment->user_id;
    }

    public function delete(User $user, Comment $comment)
    {
        // only the author can delete
        return $user->id === $comment->user_id;
    }
}
