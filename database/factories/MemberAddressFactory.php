<?php

namespace Database\Factories;

use App\Enums\AddressType;
use App\Models\Member;
use App\Models\MemberAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MemberAddress>
 */
class MemberAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'member_id' => Member::factory(),
            'type' => fake()->randomElement(AddressType::cases()),
            'label' => fake()->randomElement(['Home', 'Work', null]),
            'recipient_name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'address_line_1' => fake()->streetAddress(),
            'address_line_2' => null,
            'city' => fake()->city(),
            'state' => null,
            'postal_code' => fake()->postcode(),
            'country' => fake()->country(),
            'is_default' => false,
        ];
    }
}
