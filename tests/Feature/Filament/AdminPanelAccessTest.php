<?php

use App\Models\Member;
use App\Models\User;
use Filament\Models\Contracts\FilamentUser;

test('guests are redirected to the login page', function (): void {
    $this->get('/admin')->assertRedirect('/admin/login');
});

test('an authenticated admin user can view the dashboard', function (): void {
    $this->actingAs(User::factory()->create(), 'web')
        ->get('/admin')
        ->assertSuccessful();
});

test('the web guard authenticates against the users provider, not members', function (): void {
    expect(config('auth.guards.web.provider'))->toBe('users')
        ->and(config('auth.providers.users.model'))->toBe(User::class)
        ->and(config('auth.guards.member-api.provider'))->toBe('members')
        ->and(config('auth.guards.member-api.driver'))->toBe('sanctum')
        ->and(config('auth.guards.admin-api.provider'))->toBe('users')
        ->and(config('auth.guards.admin-api.driver'))->toBe('sanctum');
});

test('the member model does not implement the Filament panel access contract', function (): void {
    expect(Member::class)->not->toImplement(FilamentUser::class);
});
