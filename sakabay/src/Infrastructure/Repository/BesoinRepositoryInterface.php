<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Besoin;

/**
 * Interface BesoinRepositoryInterface.
 */
interface BesoinRepositoryInterface
{
    public function save(Besoin $besoin): void;

    public function delete(Besoin $besoin): void;
}
