<?php

namespace App\Services;

use App\Interfaces\AdminAuthServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AdminAuthService implements AdminAuthServiceInterface
{
    /**
     * @param  array<string, mixed>  $credentials
     */
    public function verifyCredentials(array $credentials): ?User
    {
        $user = User::query()->where('email', $credentials['email'] ?? null)->first();

        if (! $user instanceof User || ! Hash::check($credentials['password'] ?? '', $user->password)) {
            return null;
        }

        return $user;
    }

    public function issueToken(User $user): string
    {
        return JWTAuth::fromUser($user);
    }

    public function logout(): void
    {
        auth('admin-api')->logout();
    }
}
