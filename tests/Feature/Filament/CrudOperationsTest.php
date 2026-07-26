<?php

use App\Enums\OrderStatus;
use App\Enums\ProductStatus;
use App\Filament\Resources\Brands\Pages\CreateBrand;
use App\Filament\Resources\Brands\Pages\EditBrand;
use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->actingAs(User::factory()->create());
});

test('an admin can create a brand', function (): void {
    Livewire::test(CreateBrand::class)
        ->fillForm(['name' => 'Wayne Enterprises', 'slug' => 'wayne-enterprises'])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('brands', ['slug' => 'wayne-enterprises']);
});

test('an admin can edit a brand', function (): void {
    $brand = Brand::factory()->create(['name' => 'Old Name']);

    Livewire::test(EditBrand::class, ['record' => $brand->getRouteKey()])
        ->fillForm(['name' => 'New Name'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($brand->fresh()->name)->toBe('New Name');
});

test('an admin can create a nested category', function (): void {
    $parent = Category::factory()->create(['name' => 'Electronics', 'slug' => 'electronics']);

    Livewire::test(CreateCategory::class)
        ->fillForm([
            'parent_id' => $parent->id,
            'name' => 'Tablets',
            'slug' => 'tablets',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('categories', ['slug' => 'tablets', 'parent_id' => $parent->id]);
});

test('an admin can create a product with category, brand, and stock', function (): void {
    $category = Category::factory()->create();
    $brand = Brand::factory()->create();

    Livewire::test(CreateProduct::class)
        ->fillForm([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Test Widget',
            'slug' => 'test-widget',
            'sku' => 'SKU-TESTWID',
            'price' => 49.99,
            'stock' => 25,
            'status' => 'active',
            'featured' => false,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('products', ['slug' => 'test-widget', 'stock' => 25]);
});

test('an admin can update product stock and status', function (): void {
    $product = Product::factory()->create(['stock' => 5, 'status' => 'draft']);

    Livewire::test(EditProduct::class, ['record' => $product->getRouteKey()])
        ->fillForm(['stock' => 40, 'status' => 'active'])
        ->call('save')
        ->assertHasNoFormErrors();

    $product->refresh();

    expect($product->stock)->toBe(40)
        ->and($product->status)->toBe(ProductStatus::Active);
});

test('an admin can update an order status', function (): void {
    $order = Order::factory()->create(['status' => OrderStatus::Pending]);

    Livewire::test(EditOrder::class, ['record' => $order->getRouteKey()])
        ->fillForm(['status' => 'shipping'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($order->fresh()->status)->toBe(OrderStatus::Shipping);
});
