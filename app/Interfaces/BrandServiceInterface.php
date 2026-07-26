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
}
