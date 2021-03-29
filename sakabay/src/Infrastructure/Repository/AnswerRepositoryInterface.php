<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Answer;

/**
 * Interface AnswerRepositoryInterface.
 */
interface AnswerRepositoryInterface
{
    public function save(Answer $answer): void;

    public function delete(Answer $answer): void;
}
