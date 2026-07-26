<?php

use App\Models\Member;
use App\Models\User;

test('a member token cannot access admin routes', function (): void {
    $member = Member::factory()->create();
    $token = $member->createToken('member-token', ['member-api'])->plainTextToken;

    $this->withToken($token)
        ->getJson('/api/v1/admin/profile')
        ->assertUnauthorized();

    $this->withToken($token)
        ->getJson('/api/v1/admin/products')
        ->assertUnauthorized();
});

test('an admin token cannot access member routes', function (): void {
    $admin = User::factory()->create();
    $token = $admin->createToken('admin-token', ['admin-api'])->plainTextToken;

    $this->withToken($token)
        ->getJson('/api/v1/member/profile')
        ->assertUnauthorized();

    $this->withToken($token)
        ->getJson('/api/v1/cart')
        ->assertUnauthorized();
});

test('a member token without the member-api ability is rejected even though the guard matches', function (): void {
    $member = Member::factory()->create();
    $token = $member->createToken('scoped-token', ['some-other-ability'])->plainTextToken;

    $this->withToken($token)
        ->getJson('/api/v1/member/profile')
        ->assertForbidden();
});

test('an admin token without the admin-api ability is rejected even though the guard matches', function (): void {
    $admin = User::factory()->create();
    $token = $admin->createToken('scoped-token', ['some-other-ability'])->plainTextToken;

    $this->withToken($token)
        ->getJson('/api/v1/admin/profile')
        ->assertForbidden();
});
