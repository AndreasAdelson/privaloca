<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Subscription;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class SubscriptionRepository extends AbstractRepository implements SubscriptionRepositoryInterface
{

    /**
     * subscription code field for type "PREMIUM"
     */
    const PREMIUM_CODE = 'PRE';

    /**
     * subscription code field for type "FREE"
     */
    const FREE_CODE = 'FREE';

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(Subscription::class));
    }

    public function save(Subscription $subscription): void
    {
        $this->_em->persist($subscription);
        $this->_em->flush($subscription);
    }

    public function delete(Subscription $subscription): void
    {
        $this->_em->remove($subscription);
        $this->_em->flush($subscription);
    }
}
