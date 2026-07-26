<?php

namespace App\Services;

use App\Interfaces\MemberAuthServiceInterface;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;

class MemberAuthService implements MemberAuthServiceInterface
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function register(array $data): Member
    {
        return Member::query()->create($data);
    }

    /**
     * @param  array<string, mixed>  $credentials
     */
    public function verifyCredentials(array $credentials): ?Member
    {
        $member = Member::query()->where('email', $credentials['email'] ?? null)->first();

        if (! $member instanceof Member || ! Hash::check($credentials['password'] ?? '', $member->password)) {
            return null;
        }

        return $member;
    }

    public function issueToken(Member $member): string
    {
        return $member->createToken('member-token', ['member-api'])->plainTextToken;
    }

    public function logout(Member $member): void
    {
        $member->currentAccessToken()->delete();
    }
}
