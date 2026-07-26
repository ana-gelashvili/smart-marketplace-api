<?php

namespace App\Traits;

use App\Models\Member;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

trait ResolvesAuthenticatedMember
{
    protected function authenticatedMember(Request $request): Member
    {
        $member = $request->user();

        if (! $member instanceof Member) {
            throw new AuthenticationException;
        }

        return $member;
    }
}
