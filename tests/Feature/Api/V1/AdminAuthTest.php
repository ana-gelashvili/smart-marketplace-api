<?php

use App\Models\User;

test('an admin can login with valid credentials', function (): void {
    User::factory()->create(['email' => 'admin-login@example.com']);

    $this->postJson('/api/v1/admin/login', [
        'email' => 'admin-login@example.com',
        'password' => 'password',
    ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.admin.email', 'admin-login@example.com')
        ->assertJsonPath('data.token', fn (string $token) => str_contains($token, '|'));
});

test('an admin cannot login with invalid credentials', function (): void {
    User::factory()->create(['email' => 'admin-login@example.com']);

    $this->postJson('/api/v1/admin/login', [
        'email' => 'admin-login@example.com',
        'password' => 'wrong-password',
    ])->assertUnauthorized();
});

test('an admin can view their profile with a valid token', function (): void {
    $admin = User::factory()->create();
    $token = $admin->createToken('admin-token', ['admin-api'])->plainTextToken;

    $this->withToken($token)
        ->getJson('/api/v1/admin/profile')
        ->assertOk()
        ->assertJsonPath('data.email', $admin->email);
});

test('an admin profile request without a token is rejected', function (): void {
    $this->getJson('/api/v1/admin/profile')->assertUnauthorized();
});

test('an admin can logout, which revokes the current token', function (): void {
    $admin = User::factory()->create();
    $token = $admin->createToken('admin-token', ['admin-api'])->plainTextToken;

    $this->withToken($token)
        ->postJson('/api/v1/admin/logout')
        ->assertOk();

    expect($admin->tokens()->count())->toBe(0);

    // The Sanctum guard instance caches its resolved user for the lifetime of
    // the container; force it to re-resolve so the deleted token is honored
    // on this next call within the same test.
    auth()->forgetGuards();

    $this->withToken($token)
        ->getJson('/api/v1/admin/profile')
        ->assertUnauthorized();
});
