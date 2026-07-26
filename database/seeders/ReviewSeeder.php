<?php

namespace Database\Seeders;

use App\Enums\ProductStatus;
use App\Models\Member;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $members = Member::all();
        $products = Product::where('status', ProductStatus::Active)->get();

        foreach ($products->random(min(12, $products->count())) as $product) {
            $reviewers = $members->random(min(random_int(1, 4), $members->count()));

            foreach ($reviewers as $member) {
                Review::factory()->create([
                    'member_id' => $member->id,
                    'product_id' => $product->id,
                ]);
            }
        }
    }
}
