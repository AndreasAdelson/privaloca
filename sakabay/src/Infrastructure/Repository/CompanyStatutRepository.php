<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\CompanyStatut;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class CompanyStatutRepository extends AbstractRepository implements CompanyStatutRepositoryInterface
{

    /**
     * Category code field for type "Beauté"
     */
    const EN_COURS_CODE = 'ENC';

    /**
     * Category code field for type "Beauté"
     */
    const VALIDE_CODE = 'VAL';
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

    public function getENCCompanyStatut()
    {
        $query = $this->createQueryBuilder('ca')->distinct(true);
        $query->where('ca.code = (:codes)');
        $query->setParameter('codes', CompanyStatutRepository::EN_COURS_CODE);

        return $query->getQuery()->getResult();
    }

    public function getVALCompanyStatut()
    {
        $query = $this->createQueryBuilder('ca')->distinct(true);
        $query->where('ca.code = (:codes)');
        $query->setParameter('codes', CompanyStatutRepository::VALIDE_CODE);

        return $query->getQuery()->getResult();
    }
}
