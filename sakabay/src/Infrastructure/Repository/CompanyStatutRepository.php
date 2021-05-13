<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\CompanyStatut;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class CompanyStatutRepository extends AbstractRepository implements CompanyStatutRepositoryInterface
{

    /**
     * companyStatut code field for type "En cours"
     */
    const EN_COURS_CODE = 'ENC';

    /**
     * companyStatut code field for type "Validé"
     */
    const VALIDE_CODE = 'VAL';

    /**
     * companyStatut code field for type "Refusé"
     */
    const REFUSED_CODE = 'REF';
    /**
     * CompanyStatutRepository constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(CompanyStatut::class));
    }

    public function save(CompanyStatut $companyStatut): void
    {
        $this->_em->persist($companyStatut);
        $this->_em->flush($companyStatut);
    }

    public function delete(CompanyStatut $companyStatut): void
    {
        $this->_em->remove($companyStatut);
        $this->_em->flush($companyStatut);
    }
}
