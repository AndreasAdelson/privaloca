<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Company;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class CompanyRepository extends AbstractRepository implements CompanyRepositoryInterface
{
    /**
     * CompanyRepository constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(Company::class));
    }

    public function save(Company $company): void
    {
        $this->_em->persist($company);
        $this->_em->flush($company);
    }

    public function delete(Company $company): void
    {
        $this->_em->remove($company);
        $this->_em->flush($company);
    }
}
