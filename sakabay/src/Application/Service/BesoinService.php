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

    public function getBesoinAnsweredByCompany(int $besoinId, int $companyId)
    {
        return $this->besoinRepository->getBesoinAnsweredByCompany($besoinId, $companyId);
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

    public function getPaginatedBesoinByUserId(
        $utilisateur = '',
        $codeStatut = '',
        $company = '',
        $currentPage = 1,
        $perPage = PHP_INT_MAX ? PHP_INT_MAX : 10
    ) {
        $besoins = $this->besoinRepository->getPaginatedBesoinByUserId($utilisateur, $codeStatut, $company, 'false');

        return $this->paginateArray($besoins, $perPage, $currentPage);
    }


    public function getCountBesoinByUserId(
        $utilisateur = '',
        $codeStatut = '',
        $company = '',
        $isCounting = 'true'
    ) {
        return $this->besoinRepository
            ->getPaginatedBesoinByUserId($utilisateur, $codeStatut, $company, $isCounting);
    }
    /**
     * Retourne une page, potentiellement triée et filtrée des besoins particuliers pour les entreprises abonnées.
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
        $category = '',
        $sousCategory = '',
        $company = 'false',
        $currentPage = 1,
        $perPage = PHP_INT_MAX ? PHP_INT_MAX : 10
    ) {

        $opportunities =  $this->besoinRepository
            ->getPaginatedOpportunityList($category, $sousCategory, 'false', $company);
        return $this->paginateArray($opportunities, $perPage, $currentPage);
    }

    public function getCountOpportunities(
        $category = '',
        $sousCategory = '',
        $isCounting = 'true',
        $company = 'false'
    ) {
        return $this->besoinRepository
            ->getPaginatedOpportunityList($category, $sousCategory, $isCounting, $company);
    }

    /**
     * Retourne une page, potentiellement triée et filtrée des besoins avec demande de devis pour les entreprises abonnées.
     *

     *
     * @param string $sortBy
     * @param bool   $descending
     * @param int    $currentPage
     * @param int    $perPage
     *
     * @return Pagerfanta
     */
    public function getPaginatedOpportunityWithRequestedQuoteList(
        $company = '',
        $currentPage = 1,
        $perPage = PHP_INT_MAX ? PHP_INT_MAX : 10
    ) {

        $opportunities =  $this->besoinRepository
            ->getPaginatedOpportunityWithRequestedQuoteList($company, false);
        return $this->paginateArray($opportunities, $perPage, $currentPage);
    }


    public function getCountOpportunitiesWithRequestedQuote(
        $company = '',
        $isCounting = true
    ) {
        return $this->besoinRepository
            ->getPaginatedOpportunityWithRequestedQuoteList($company, $isCounting);
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
