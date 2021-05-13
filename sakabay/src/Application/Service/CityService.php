<?php

namespace App\Application\Service;

use App\Domain\Model\City;
use App\Infrastructure\Repository\CityRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CityService
{
    /**
     * @var CityRepositoryInterface
     */
    private $cityRepository;

    /**
     * CityRestController constructor.
     */
    public function __construct(CityRepositoryInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    /// Afficher un City
    public function getCity(int $cityId): ?City
    {
        return $this->cityRepository->find($cityId);
    }

    public function getAllCitys(): ?array
    {
        return $this->cityRepository->findAll();
    }

    public function deleteCity(int $cityId): void
    {
        $city = $this->cityRepository->find($cityId);
        if (!$city) {
            throw new EntityNotFoundException('City with id ' . $cityId . ' does not exist!');
        }
        $this->cityRepository->delete($city);
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
        return $this->cityRepository
            ->getPaginatedList($sortBy, $descending, $filterFields, $filterText, $currentPage, $perPage);
    }

    /**
     * Return a list of User matching given criterias.
     *
     * @param string $filter[autocomplete] Name or first name or login of a user
     */
    public function findCitiesForAutocomplete(array $filter): array
    {
        return $this->cityRepository->findCitiesForAutocomplete($filter);
    }
}
