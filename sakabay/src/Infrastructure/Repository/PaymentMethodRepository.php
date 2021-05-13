<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\PaymentMethod;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class PaymentMethodRepository extends AbstractRepository implements PaymentMethodRepositoryInterface
{

    /**
     * PaymentMethodRepository constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(PaymentMethod::class));
    }

    public function save(PaymentMethod $paymentMethod): void
    {
        $this->_em->persist($paymentMethod);
        $this->_em->flush($paymentMethod);
    }

    public function delete(PaymentMethod $paymentMethod): void
    {
        $this->_em->remove($paymentMethod);
        $this->_em->flush($paymentMethod);
    }

    public function getDefaultPaymentMethod($companyId = '')
    {
        $qb = $this->createQueryBuilder('pm');
        if (!empty($companyId)) {
            $qb->leftJoin('pm.company', 'company')
                ->andWhere('company.id = :companyId')
                ->andWhere('pm.defaultMethod = 1')
                ->setParameter('companyId', $companyId);
            return $qb->getQuery()->getSingleResult();
        } else {
            return null;
        }
    }
}
