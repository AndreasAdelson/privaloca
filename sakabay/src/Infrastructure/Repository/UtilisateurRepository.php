<?php

// src/Repository/UserRepository.php
namespace App\Infrastructure\Repository;

use App\Domain\Model\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends AbstractRepository implements UtilisateurRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(Utilisateur::class));
    }

    public function loadUserByUsername($usernameOrEmail)
    {
        return $this->createQuery(
            'SELECT u
                FROM App\Domain\Model\Utilisateur u
                WHERE u.email = :query'
        )
            ->setParameter('query', $usernameOrEmail)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(Utilisateur $utilisateur): void
    {
        $this->_em->persist($utilisateur);
        $this->_em->flush($utilisateur);
    }

    public function delete(Utilisateur $utilisateur): void
    {
        $this->_em->remove($utilisateur);
        $this->_em->flush($utilisateur);
    }

    /**
     * Return a list of User matching given criterias.
     *
     * @param string $filter[autocomplete] Name or first name or login of a user
     */
    public function findUsersForAutocomplete(array $filter): array
    {
        $name = $filter['autocomplete'];
        $name = mb_strtolower("%$name%");
        $queryFilter = "LOWER(CONCAT(user.firstName, ' ', user.lastName)) LIKE :name
                        OR LOWER(CONCAT(user.lastName, ' ', user.firstName)) LIKE :name
                        OR LOWER(user.login) LIKE :name";
        $query = $this->createQueryBuilder('user');

        return $query->andWhere($queryFilter)
            ->setParameter('name', $name)
            ->addOrderBy('user.lastName')
            ->addOrderBy('user.firstName')
            ->getQuery()
            ->getResult();
    }
}
