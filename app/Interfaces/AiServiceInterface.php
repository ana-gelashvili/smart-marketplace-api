<?php

namespace App\Interfaces;

interface AiServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getRecommendations(int $memberId): array;
}
