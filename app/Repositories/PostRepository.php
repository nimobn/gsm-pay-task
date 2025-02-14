<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository
{
    public function getUserPosts(User $user): LengthAwarePaginator
    {
        return $user->posts()->paginate(5);
    }

    public function findPost(int $id): ?Post
    {
        return Post::find($id);
    }
}