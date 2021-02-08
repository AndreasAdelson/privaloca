<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\BesoinStatut;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class BesoinStatutRepository extends AbstractRepository implements BesoinStatutRepositoryInterface
{
    /**
     * BesoinStatutRepository constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(BesoinStatut::class));
    }

    public function save(BesoinStatut $besoinStatut): void
    {
        $this->_em->persist($besoinStatut);
        $this->_em->flush($besoinStatut);
    }

    public function delete(BesoinStatut $besoinStatut): void
    {
        $this->_em->remove($besoinStatut);
        $this->_em->flush($besoinStatut);
    }
}
