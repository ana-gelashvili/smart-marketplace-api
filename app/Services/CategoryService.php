<?php

namespace App\Services;

use App\Interfaces\CategoryServiceInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService implements CategoryServiceInterface
{
    /**
     * Maximum nesting depth eagerly loaded for the category tree.
     */
    private const MAX_DEPTH = 5;

    /**
     * @return Collection<int, Category>
     */
    public function getNestedTree(): Collection
    {
        $with = implode('.', array_fill(0, self::MAX_DEPTH, 'children'));

        return Category::query()
            ->whereNull('parent_id')
            ->with($with)
            ->get();
    }

    public function findById(int $id): Category
    {
        return Category::query()->findOrFail($id);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Category
    {
        return Category::query()->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        return $category->fresh() ?? $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
