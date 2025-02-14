<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function getSortedUsers(): LengthAwarePaginator
    {
        return User::withSum('posts as total_posts_views', 'views_count')
            ->orderByDesc('total_posts_views')
            ->paginate(10);
    }

    public function updateAvatar(User $user, $avatarPath): User
    {
        $user->update([
            'avatar' => $avatarPath
        ]);

        return $user;
    }
}