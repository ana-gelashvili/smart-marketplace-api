<?php

namespace App\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryServiceInterface
{
    /**
     * @return Collection<int, Category>
     */
    public function getNestedTree(): Collection;
}
