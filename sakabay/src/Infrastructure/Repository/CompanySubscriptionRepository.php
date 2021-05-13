<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\CompanySubscription;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class CompanySubscriptionRepository extends AbstractRepository implements CompanySubscriptionRepositoryInterface
{

    /**
     * CompanySubscriptionRepository constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(CompanySubscription::class));
    }

    public function save(CompanySubscription $companySubscription): void
    {
        $this->_em->persist($companySubscription);
        $this->_em->flush($companySubscription);
    }

    public function delete(CompanySubscription $companySubscription): void
    {
        $this->_em->remove($companySubscription);
        $this->_em->flush($companySubscription);
    }

    public function getActiveSubscription($company = '', $onStipeId, $isCanceled)
    {
        $qb = $this->createQueryBuilder('cs');
        if (!empty($company)) {
            $today = \DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:m:s"));
            $qb->leftJoin('cs.company', 'company')
                ->where('company.id = :companyId')
                ->andWhere('cs.dtFin >= :today')
                ->setParameters([
                    'companyId' => $company,
                    'today' => $today
                ]);
            if ($onStipeId) {
                $qb->andWhere('cs.stripeId IS NOT NULL');
            }
            if ($isCanceled) {
                $qb->leftJoin('cs.subscriptionStatus', 'subscriptionStatus')
                    ->andWhere('subscriptionStatus.code =:subscriptionStatusCode')
                    ->setParameter('subscriptionStatusCode',   SubscriptionStatusRepository::CANCEL_CODE);
            }
            return $qb->getQuery()->getOneOrNullResult();
        } else {
            return [];
        }
    }
}
