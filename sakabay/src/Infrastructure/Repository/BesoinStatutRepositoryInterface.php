<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\BesoinStatut;

/**
 * Interface BesoinStatutRepositoryInterface.
 */
interface BesoinStatutRepositoryInterface
{
    public function save(BesoinStatut $besoinStatut): void;

    public function delete(BesoinStatut $besoinStatut): void;
}
