<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\PaymentMethod;

/**
 * Interface PaymentMethodRepositoryInterface.
 */
interface PaymentMethodRepositoryInterface
{
    public function save(PaymentMethod $paymentMethod): void;

    public function delete(PaymentMethod $paymentMethod): void;
}
