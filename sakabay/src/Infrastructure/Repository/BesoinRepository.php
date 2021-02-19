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

    /**
     * Ici on sépare les cas particulier par requêtes.
     *
     * Dans un premier temps on gère la requête pour les entreprises
     * ayant sélectionnées une ou plusieurs sous-catégories.
     * La requête doit rendre tous les besoins sans sous-catégories ayant
     * la même catégorie que l'entreprise. Ainsi que les besoins ayant au
     * moins une sous-catégorie commune à l'entreprise.
     *
     * Dans un second temps on gère la requête pour les entreprises
     * n'ayant pas encore sélectionnées de sous catégorie.
     * La requête doit rendre uniquement les besoins sans sous-catégories
     * et qui correspondent à la catégorie de l'entreprise.
     */
    public function getPaginatedOpportunityList(
        $sortBy = 'id',
        $descending = false,
        $category = '',
        $sousCategory = null,
        $isCounting = false
    ) {
        $qb = $this->createQueryBuilder('b');
        if ($isCounting) {
            $qb->select('count(DISTINCT b.id)');
        }
        if (is_array($sousCategory) && !empty($sousCategory)) {
            $qb->leftJoin('b.category', 'category')
                ->andWhere('category.code =:category')
                ->setParameter('category', $category);
            $qb->leftJoin('b.sousCategorys', 'sousCategory');
            $orStatements = $qb->expr()->orX();
            foreach ($sousCategory as $sousCategoryCode) {
                $orStatements->add(
                    $qb->expr()->like('sousCategory.code', $qb->expr()->literal('%' . $sousCategoryCode . '%'))
                );
            }
            $qb->orWhere($orStatements);
        } else {
            $qb->leftJoin('b.category', 'category')
                ->andWhere('category.code =:category')
                ->setParameter('category', $category);
            $qb->leftJoin('b.sousCategorys', 'sousCategory')
                ->andWhere('sousCategory.id is NULL');
        }

        if ($isCounting) {
            return $qb->getQuery()->getSingleScalarResult();
        }

        $qb->orderBy('b.dtCreated', 'DESC');
        return $qb->getQuery()->getResult();
    }
}
