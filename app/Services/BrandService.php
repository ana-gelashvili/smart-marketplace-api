<?php

namespace App\Services;

use App\Interfaces\BrandServiceInterface;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;

class BrandService implements BrandServiceInterface
{
    /**
     * @return Collection<int, Brand>
     */
    public function list(): Collection
    {
        return Brand::query()->orderBy('name')->get();
    }

    public function findById(int $id): Brand
    {
        return Brand::query()->findOrFail($id);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Brand
    {
        return Brand::query()->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Brand $brand, array $data): Brand
    {
        $brand->update($data);

        return $brand->fresh() ?? $brand;
    }

    public function delete(Brand $brand): void
    {
        $brand->delete();
    }
}
