<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private AuthRepository $authRepository,
    ) {}

    public function registerUser(array $userData): User
    {
        return $this->authRepository->createUser($userData);
    }

    public function loginUser(array $credentials): array|ValidationException
    {
        if(!Auth::attempt($credentials)) {
            throw new AuthenticationException('The provided credentials are incorrect.');
        }

        $user = Auth::user();

        return [
            'user' => $user,
            'accessToken' => $user->createToken('authToken')->accessToken
        ];
    }
}