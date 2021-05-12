<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Advantage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class AdvantageRepository extends AbstractRepository implements AdvantageRepositoryInterface
{

    /**
     * AdvantageRepository constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(Advantage::class));
    }

    public function save(Advantage $advantage): void
    {
        $this->_em->persist($advantage);
        $this->_em->flush($advantage);
    }

    public function delete(Advantage $advantage): void
    {
        $this->_em->remove($advantage);
        $this->_em->flush($advantage);
    }
}
