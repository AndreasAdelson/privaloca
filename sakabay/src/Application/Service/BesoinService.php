<?php

namespace App\Application\Service;

use App\Domain\Model\Besoin;
use App\Infrastructure\Repository\BesoinRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
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
     * Retourne une page, potentiellement triée et filtrée.
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
}
