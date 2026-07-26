<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = implode(' ', array_map('strval', (array) fake()->unique()->words(2)));

        return [
            'parent_id' => null,
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
