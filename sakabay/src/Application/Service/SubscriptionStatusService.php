<?php

namespace App\Application\Service;

use App\Domain\Model\SubscriptionStatus;
use App\Infrastructure\Repository\SubscriptionStatusRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;

class SubscriptionStatusService
{
    /**
     * @var SubscriptionStatusRepositoryInterface
     */
    private $subscriptionStatusRepository;

    /**
     * SubscriptionStatusRestController constructor.
     */
    public function __construct(SubscriptionStatusRepositoryInterface $subscriptionStatusRepository)
    {
        $this->subscriptionStatusRepository = $subscriptionStatusRepository;
    }

    /// Afficher un SubscriptionStatus
    public function getSubscriptionStatus(int $subscriptionStatusId): ?SubscriptionStatus
    {
        return $this->subscriptionStatusRepository->find($subscriptionStatusId);
    }

    public function getAllSubscriptionStatus(): ?array
    {
        return $this->subscriptionStatusRepository->findAll();
    }

    public function deleteSubscriptionStatus(int $subscriptionStatusId): void
    {
        $subscriptionStatus = $this->subscriptionStatusRepository->find($subscriptionStatusId);
        if (!$subscriptionStatus) {
            throw new EntityNotFoundException('SubscriptionStatus with id ' . $subscriptionStatusId . ' does not exist!');
        }
        $this->subscriptionStatusRepository->delete($subscriptionStatus);
    }

    public function getSubscriptionStatusByCode(string $code)
    {
        return $this->subscriptionStatusRepository->findOneBy(['code' => $code]);
    }



    /**
     * Retourne une page, potentiellement triée et filtrée.
     *
     * @author vbioret
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
        return $this->subscriptionStatusRepository
            ->getPaginatedList($sortBy, $descending, $filterFields, $filterText, $currentPage, $perPage);
    }
}
