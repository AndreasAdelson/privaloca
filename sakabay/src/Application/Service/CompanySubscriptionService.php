<?php

namespace App\Application\Service;

use App\Domain\Model\CompanySubscription;
use App\Infrastructure\Repository\CompanySubscriptionRepositoryInterface;

class CompanySubscriptionService
{
    /**
     * @var CompanySubscriptionRepositoryInterface
     */
    private $companySubscriptionRepository;

    /**
     * CompanySubscriptionRestController constructor.
     */
    public function __construct(CompanySubscriptionRepositoryInterface $companySubscriptionRepository)
    {
        $this->companySubscriptionRepository = $companySubscriptionRepository;
    }

    /// Afficher un CompanySubscription
    public function getCompanySubscription(int $companySubscriptionId): ?CompanySubscription
    {
        return $this->companySubscriptionRepository->find($companySubscriptionId);
    }

    public function getAllCompanySubscriptions(): ?array
    {
        return $this->companySubscriptionRepository->findAll();
    }

    public function getActiveSubscription($companyId, $onStripeId, $isCanceled)
    {
        return $this->companySubscriptionRepository->getActiveSubscription($companyId, $onStripeId, $isCanceled);
    }

    public function findCompanySubscriptionByStripeId($stripeSubscriptionId): ?CompanySubscription
    {
        $companySubscription = $this->companySubscriptionRepository->findOneBy([
            'stripeId' => $stripeSubscriptionId
        ]);
        if (!$companySubscription) {
            throw new \Exception('Somehow we have no companySubscription id ' . $stripeSubscriptionId);
        }

        return $companySubscription;
    }

    public function endCompanySubscriptionByStripeId($stripeSubscriptionId, $dtFin): void
    {
        $companySubscription = $this->findCompanySubscriptionByStripeId($stripeSubscriptionId);
        $companySubscription->setDtFin($dtFin);
        $this->companySubscriptionRepository->save($companySubscription);
    }

    public function setStatusCompanySubscriptionByStripeId($stripeSubscriptionId, $subscritonStatus): void
    {
        $companySubscription = $this->findCompanySubscriptionByStripeId($stripeSubscriptionId);
        $companySubscription->setSubscriptionStatus($subscritonStatus);
        $this->companySubscriptionRepository->save($companySubscription);
    }
}
