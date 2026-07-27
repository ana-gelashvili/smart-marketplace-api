<?php

namespace App\Services;

use App\Interfaces\AiServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService implements AiServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getRecommendations(int $memberId): array
    {
        $response = Http::baseUrl(config('services.ai.url'))
            ->withHeaders(['X-AI-SERVICE-KEY' => config('services.ai.key')])
            ->get("/api/v1/recommendations/{$memberId}");

        if ($response->failed()) {
            Log::error('AI service recommendations request failed', [
                'member_id' => $memberId,
                'status' => $response->status(),
            ]);

            return [
                'success' => false,
                'member_id' => $memberId,
                'recommendations' => [],
            ];
        }

        return $response->json();
    }
}
