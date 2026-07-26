<?php

namespace App\Interfaces;

use App\Models\User;

interface AdminAuthServiceInterface
{
    /**
     * @param  array<string, mixed>  $credentials
     */
    public function verifyCredentials(array $credentials): ?User;

    public function issueToken(User $user): string;

    public function logout(User $user): void;
}
