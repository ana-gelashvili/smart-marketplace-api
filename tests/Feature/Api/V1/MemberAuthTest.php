<?php

use App\Models\Member;

test('a member can register and receives a bearer token', function (): void {
    $response = $this->postJson('/api/v1/member/register', [
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.member.email', 'ada@example.com')
        ->assertJsonPath('data.token', fn (string $token) => str_contains($token, '|'));

    $this->assertDatabaseHas('members', ['email' => 'ada@example.com']);
});

test('a member can login with valid credentials', function (): void {
    Member::factory()->create(['email' => 'login@example.com']);

    $this->postJson('/api/v1/member/login', [
        'email' => 'login@example.com',
        'password' => 'password',
    ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.member.email', 'login@example.com');
});

test('a member cannot login with invalid credentials', function (): void {
    Member::factory()->create(['email' => 'login@example.com']);

    $this->postJson('/api/v1/member/login', [
        'email' => 'login@example.com',
        'password' => 'wrong-password',
    ])->assertUnauthorized();
});

test('a member can view their profile with a valid token', function (): void {
    $member = Member::factory()->create();
    $token = $member->createToken('member-token', ['member-api'])->plainTextToken;

    $this->withToken($token)
        ->getJson('/api/v1/member/profile')
        ->assertOk()
        ->assertJsonPath('data.id', $member->uuid);
});

test('a member profile request without a token is rejected', function (): void {
    $this->getJson('/api/v1/member/profile')->assertUnauthorized();
});

test('a member can logout, which revokes the current token', function (): void {
    $member = Member::factory()->create();
    $token = $member->createToken('member-token', ['member-api'])->plainTextToken;

    $this->withToken($token)
        ->postJson('/api/v1/member/logout')
        ->assertOk();

    expect($member->tokens()->count())->toBe(0);

    // The Sanctum guard instance caches its resolved user for the lifetime of
    // the container; force it to re-resolve so the deleted token is honored
    // on this next call within the same test.
    auth()->forgetGuards();

    $this->withToken($token)
        ->getJson('/api/v1/member/profile')
        ->assertUnauthorized();
});
