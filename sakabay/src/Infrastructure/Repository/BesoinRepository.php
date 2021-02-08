<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Besoin;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class BesoinRepository extends AbstractRepository implements BesoinRepositoryInterface
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(Besoin::class));
    }

    public function save(Besoin $companyStatut): void
    {
        $this->_em->persist($companyStatut);
        $this->_em->flush($companyStatut);
    }

    public function delete(Besoin $companyStatut): void
    {
        $this->_em->remove($companyStatut);
        $this->_em->flush($companyStatut);
    }

    public function getBesoinByUserId($utilisateur = '', string $codeStatut)
    {
        $qb = $this->createQueryBuilder('b');
        $qb->leftJoin('b.author', 'author')
            ->andWhere('author.id = :utilisateurId')
            ->setParameter('utilisateurId', $utilisateur);

        if (!empty($codeStatut)) {
            $qb->leftJoin('b.besoinStatut', 'besoinStatut')
                ->andWhere('besoinStatut.code = :codeStatut')
                ->setParameter('codeStatut', $codeStatut);
        }
        $qb->orderBy('b.dtCreated', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
