<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Category;

/**
 * Interface CategoryRepositoryInterface.
 */
interface CategoryRepositoryInterface
{
    public function save(Category $category): void;

    public function delete(Category $category): void;
}
