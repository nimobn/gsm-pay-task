<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class PostService
{
    public function __construct(
        private PostRepository $postRepository,
    ) {}

    public function getUserPosts(): LengthAwarePaginator
    {
        $user = Auth::user();
        return $this->postRepository->getUserPosts($user);
    }

    public function showPost(int $postId): Post
    {
        return $this->postRepository->findPost($postId);
    }
}