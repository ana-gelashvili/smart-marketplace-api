<?php

namespace App\Interfaces;

use App\Models\Member;

interface MemberAuthServiceInterface
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function register(array $data): Member;

    /**
     * @param  array<string, mixed>  $credentials
     */
    public function verifyCredentials(array $credentials): ?Member;

    public function issueToken(Member $member): string;

    public function logout(): void;
}
