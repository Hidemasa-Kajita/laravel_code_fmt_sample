<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

class UserService {
    public function __construct(private UserRepository $userRepository){}

    public function register(string $name, $email, string $password): User
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        return $user;
    }

    public function getToken(string $email, string $password): ?PersonalAccessToken
    {
        $user = $this->userRepository->getFirstWhere([
            'email' => $email,
        ]);

        if (!$user) return null;

        $isMatch = Hash::check($password, $user->password);

        if (!$isMatch) return null;

        $user->tokens()->where('tokenable_id', $user->id)->delete();

        return $user->createToken('default')->accessToken;
    }
}
