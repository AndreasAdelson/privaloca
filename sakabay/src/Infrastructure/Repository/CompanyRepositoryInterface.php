<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Company;

/**
 * Interface CompanyRepositoryInterface.
 */
interface CompanyRepositoryInterface
{
    public function save(Company $example): void;

    public function delete(Company $example): void;
}
