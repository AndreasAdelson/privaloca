<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\SubscriptionStatus;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class SubscriptionStatusRepository extends AbstractRepository implements SubscriptionStatusRepositoryInterface
{

    /**
     * subscriptionStatus code field for type "CANCELLED"
     */
    const CANCEL_CODE = 'ANN';

    /**
     * subscriptionStatus code field for type "ENDED"
     */
    const END_CODE = 'TER';

    /**
     * subscriptionStatus code field for type "VALIDATE"
     */
    const VALIDATE_CODE = 'VAL';

    /**
     * subscriptionStatus code field for type "PENDING"
     */
    const PENDING_CODE = 'ENC';

    /**
     * subscriptionStatus code field for type "CANCELLED"
     */
    const OFFER_CODE = 'OFF';


    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(SubscriptionStatus::class));
    }

    public function save(SubscriptionStatus $subscriptionStatus): void
    {
        $this->_em->persist($subscriptionStatus);
        $this->_em->flush($subscriptionStatus);
    }

    public function delete(SubscriptionStatus $subscriptionStatus): void
    {
        $this->_em->remove($subscriptionStatus);
        $this->_em->flush($subscriptionStatus);
    }
}
