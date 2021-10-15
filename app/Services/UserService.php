<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

class UserService {
    /** @param UserRepository $userRepository */
    public function __construct(private UserRepository $userRepository){}

    /**
     * Undocumented function
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @return User
     */
    public function register(string $name, string $email, string $password): User
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        return $user;
    }

    /**
     * Undocumented function
     *
     * @param string $email
     * @param string $password
     * @return string|null
     */
    public function issueToken(string $email, string $password): ?string
    {
        $user = $this->userRepository->getFirstWhere([
            'email' => $email,
        ]);

        if (!$user) return null;

        $isMatch = Hash::check($password, $user->password);

        if (!$isMatch) return null;

        $user->tokens()->where('tokenable_id', $user->id)->delete();

        return $user->createToken('default')->plainTextToken;
    }
}
