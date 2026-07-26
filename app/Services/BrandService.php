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
}
