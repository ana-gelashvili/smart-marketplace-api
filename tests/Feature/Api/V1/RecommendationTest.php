<?php

use App\Interfaces\AiServiceInterface;
use App\Models\Member;

test('an authenticated member can request recommendations', function (): void {
    $member = Member::factory()->create();
    $token = $member->createToken('member-token', ['member-api'])->plainTextToken;

    $this->mock(AiServiceInterface::class, function ($mock) use ($member): void {
        $mock->shouldReceive('getRecommendations')
            ->once()
            ->with($member->id)
            ->andReturn([
                'success' => true,
                'member_id' => $member->id,
                'recommendations' => [
                    ['product_id' => 5, 'score' => 0.95],
                ],
            ]);
    });

    $this->withToken($token)
        ->getJson('/api/v1/recommendations')
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.success', true)
        ->assertJsonPath('data.member_id', $member->id)
        ->assertJsonPath('data.recommendations.0.product_id', 5)
        ->assertJsonPath('data.recommendations.0.score', 0.95);
});

test('a recommendations request without a token is rejected', function (): void {
    $this->getJson('/api/v1/recommendations')->assertUnauthorized();
});
