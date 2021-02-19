<?php

namespace App\Application\Service;

use App\Domain\Model\Besoin;
use App\Infrastructure\Repository\BesoinRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BesoinService
{
    /**
     * @var BesoinRepositoryInterface
     */
    private $besoinRepository;

    /**
     * BesoinRestController constructor.
     */
    public function __construct(BesoinRepositoryInterface $besoinRepository)
    {
        $this->besoinRepository = $besoinRepository;
    }

    public function getBesoin(int $besoinId): ?Besoin
    {
        return $this->besoinRepository->find($besoinId);
    }

    public function getAllBesoins(): ?array
    {
        return $this->besoinRepository->findAll();
    }

    public function deleteBesoin(int $besoinId): void
    {
        $besoin = $this->besoinRepository->find($besoinId);
        if (!$besoin) {
            throw new EntityNotFoundException('Besoin with id ' . $besoinId . ' does not exist!');
        }
        $this->besoinRepository->delete($besoin);
    }

    public function getBesoinByUserId(string $utilisateur = '', string $codeStatut)
    {
        return $this->besoinRepository->getBesoinByUserId($utilisateur, $codeStatut);
    }

    /**
     * Retourne une page, potentiellement triée et filtrée pour les admins.
     *

     *
     * @param string $sortBy
     * @param bool   $descending
     * @param string $filterFields
     * @param string $filterText
     * @param int    $currentPage
     * @param int    $perPage
     *
     * @return Pagerfanta
     */
    public function getPaginatedList(
        $sortBy = 'id',
        $descending = false,
        $filterFields = '',
        $filterText = '',
        $currentPage = 1,
        $perPage = PHP_INT_MAX ? PHP_INT_MAX : 10
    ) {
        return $this->besoinRepository
            ->getPaginatedList($sortBy, $descending, $filterFields, $filterText, $currentPage, $perPage);
    }

    /**
     * Retourne une page, potentiellement triée et filtrée pour les entreprises abonnées.
     *

     *
     * @param string $sortBy
     * @param bool   $descending
     * @param int    $currentPage
     * @param int    $perPage
     *
     * @return Pagerfanta
     */
    public function getPaginatedOpportunityList(
        $sortBy = 'id',
        $descending = false,
        $category = '',
        $sousCategory = '',
        $currentPage = 1,
        $perPage = PHP_INT_MAX ? PHP_INT_MAX : 10
    ) {

        $opportunities =  $this->besoinRepository
            ->getPaginatedOpportunityList($sortBy, $descending, $category, $sousCategory, false);
        return $this->paginateArray($opportunities, $perPage, $currentPage);
    }

    public function getCountOpportunities(
        $sortBy = 'id',
        $descending = false,
        $category = '',
        $sousCategory = '',
        $isCounting = true
    ) {
        return $this->besoinRepository
            ->getPaginatedOpportunityList($sortBy, $descending, $category, $sousCategory, $isCounting);
    }

    /**
     * Retourne une page en fonction d'une requète, d'une taille et d'une position.
     *

     *
     * @param int $perPage
     * @param int $currentPage
     *
     * @throws LogicException
     * @return Pagerfanta
     */
    public function paginateArray($data, $perPage, $currentPage)
    {
        $perPage = (int) $perPage;
        if (0 >= $perPage) {
            throw new \LogicException('$perPage must be greater than 0.');
        }
        if (0 >= $currentPage) {
            throw new \LogicException('$currentPage must be greater than 0.');
        }
        $pager = new Pagerfanta(new ArrayAdapter($data));
        $pager->setMaxPerPage((int) $perPage);
        $pager->setCurrentPage((int) $currentPage);

        return $pager;
    }
}
