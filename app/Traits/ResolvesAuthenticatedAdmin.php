<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

trait ResolvesAuthenticatedAdmin
{
    protected function authenticatedAdmin(Request $request): User
    {
        $admin = $request->user();

        if (! $admin instanceof User) {
            throw new AuthenticationException;
        }

        return $admin;
    }
}
