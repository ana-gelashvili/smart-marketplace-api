<?php

use App\Filament\Resources\Brands\Pages\ListBrands;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\MemberAddresses\Pages\ListMemberAddresses;
use App\Filament\Resources\Members\Pages\ListMembers;
use App\Filament\Resources\OrderItems\Pages\ListOrderItems;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Payments\Pages\ListPayments;
use App\Filament\Resources\ProductImages\Pages\ListProductImages;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Reviews\Pages\ListReviews;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->actingAs(User::factory()->create());
});

test('every admin resource index page renders successfully', function (string $page): void {
    Livewire::test($page)->assertSuccessful();
})->with([
    ListUsers::class,
    ListCategories::class,
    ListBrands::class,
    ListProducts::class,
    ListProductImages::class,
    ListMembers::class,
    ListMemberAddresses::class,
    ListReviews::class,
    ListOrders::class,
    ListOrderItems::class,
    ListPayments::class,
]);
