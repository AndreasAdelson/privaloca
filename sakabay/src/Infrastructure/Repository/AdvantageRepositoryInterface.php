<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Advantage;

/**
 * Interface AdvantageRepositoryInterface.
 */
interface AdvantageRepositoryInterface
{
    public function save(Advantage $advantage): void;

    public function delete(Advantage $advantage): void;
}
