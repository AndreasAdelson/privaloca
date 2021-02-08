<?php

namespace App\Application\Service;

use App\Domain\Model\BesoinStatut;
use App\Infrastructure\Repository\BesoinStatutRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BesoinStatutService
{
    /**
     * @var BesoinStatutRepositoryInterface
     */
    private $besoinStatutRepository;

    /**
     * BesoinStatutRestController constructor.
     */
    public function __construct(BesoinStatutRepositoryInterface $besoinStatutRepository)
    {
        $this->besoinStatutRepository = $besoinStatutRepository;
    }

    public function getBesoinStatut(int $besoinStatutId): ?BesoinStatut
    {
        return $this->besoinStatutRepository->find($besoinStatutId);
    }

    public function getAllBesoinStatuts(): ?array
    {
        return $this->besoinStatutRepository->findAll();
    }

    public function deleteBesoinStatut(int $besoinStatutId): void
    {
        $besoinStatut = $this->besoinStatutRepository->find($besoinStatutId);
        if (!$besoinStatut) {
            throw new EntityNotFoundException('BesoinStatut with id ' . $besoinStatutId . ' does not exist!');
        }
        $this->besoinStatutRepository->delete($besoinStatut);
    }

    public function getBesoinStatutByCode(string $code)
    {
        return $this->besoinStatutRepository->findOneBy(['code' => $code]);
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
        return $this->besoinStatutRepository
            ->getPaginatedList($sortBy, $descending, $filterFields, $filterText, $currentPage, $perPage);
    }
}
