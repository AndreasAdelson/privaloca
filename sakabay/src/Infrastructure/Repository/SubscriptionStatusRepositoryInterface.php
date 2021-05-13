<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\SubscriptionStatus;

/**
 * Interface SubscriptionStatusRepositoryInterface.
 */
interface SubscriptionStatusRepositoryInterface
{
    public function save(SubscriptionStatus $subscriptionStatus): void;

    public function delete(SubscriptionStatus $subscriptionStatus): void;
}
