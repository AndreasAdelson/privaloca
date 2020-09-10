<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Category;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class CategoryRepository extends AbstractRepository implements CategoryRepositoryInterface
{
    /**
     * CategoryRepository constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(Category::class));
    }

    public function save(Category $category): void
    {
        $this->_em->persist($category);
        $this->_em->flush($category);
    }

    public function delete(Category $category): void
    {
        $this->_em->remove($category);
        $this->_em->flush($category);
    }
}
