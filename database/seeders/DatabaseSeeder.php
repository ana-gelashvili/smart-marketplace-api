<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            MemberSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            ReviewSeeder::class,
            WishlistSeeder::class,
            CartSeeder::class,
            OrderSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
