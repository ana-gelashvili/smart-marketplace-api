<?php

namespace App\Interfaces;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;

interface BrandServiceInterface
{
    /**
     * @return Collection<int, Brand>
     */
    public function list(): Collection;

    public function findById(int $id): Brand;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Brand;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Brand $brand, array $data): Brand;

    public function delete(Brand $brand): void;
}
