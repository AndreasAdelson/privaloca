<?php

namespace App\Application\Service;

use App\Domain\Model\Advantage;
use App\Infrastructure\Repository\AdvantageRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;

class AdvantageService
{
    /**
     * @var AdvantageRepositoryInterface
     */
    private $advantageRepository;

    /**
     * AdvantageRestController constructor.
     */
    public function __construct(AdvantageRepositoryInterface $advantageRepository)
    {
        $this->advantageRepository = $advantageRepository;
    }

    // Afficher un Advantage
    public function getAdvantage(int $advantageId): ?Advantage
    {
        return $this->advantageRepository->find($advantageId);
    }

    public function getAllAdvantages(): ?array
    {
        return $this->advantageRepository->findAll();
    }

    public function deleteAdvantage(int $advantageId): void
    {
        $advantage = $this->advantageRepository->find($advantageId);
        if (!$advantage) {
            throw new EntityNotFoundException('Advantage with id ' . $advantageId . ' does not exist!');
        }
        $this->advantageRepository->delete($advantage);
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
        return $this->advantageRepository
            ->getPaginatedList($sortBy, $descending, $filterFields, $filterText, $currentPage, $perPage);
    }
}
