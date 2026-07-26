<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\MemberAddress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $member = Member::factory()->create([
            'name' => 'Demo Member',
            'email' => 'member@example.com',
            'password' => Hash::make('password'),
        ]);

        $member->addresses()->create([
            'type' => 'both',
            'label' => 'Home',
            'recipient_name' => $member->name,
            'phone' => $member->phone ?? '+10000000000',
            'address_line_1' => '123 Main Street',
            'address_line_2' => null,
            'city' => 'Springfield',
            'state' => null,
            'postal_code' => '12345',
            'country' => 'United States',
            'is_default' => true,
        ]);

        Member::factory()
            ->count(9)
            ->has(MemberAddress::factory()->count(1), 'addresses')
            ->create();
    }
}
