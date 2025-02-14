<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function getUsers(): LengthAwarePaginator
    {
        return $this->userRepository->getSortedUsers();
    }

    public function updateAvatar($avatar): User
    {
        $user = Auth::user();
        $path = $avatar->store('avatars', 'public');

        return $this->userRepository->updateAvatar($user, $path);
    }
}