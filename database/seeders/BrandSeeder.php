<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Acme', 'Globex', 'Initech', 'Umbrella', 'Stark Industries'] as $name) {
            Brand::create(['name' => $name, 'slug' => Str::slug($name)]);
        }
    }
}
