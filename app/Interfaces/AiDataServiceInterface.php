<?php

namespace App\Interfaces;

use App\Models\Member;

interface AiDataServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getMemberProfile(Member $member): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getCandidateProducts(): array;
}
