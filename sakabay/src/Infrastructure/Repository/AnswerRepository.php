<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Answer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class AnswerRepository extends AbstractRepository implements AnswerRepositoryInterface
{

    /**
     * AnswerRepository constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(Answer::class));
    }

    public function save(Answer $answer): void
    {
        $this->_em->persist($answer);
        $this->_em->flush($answer);
    }

    public function delete(Answer $answer): void
    {
        $this->_em->remove($answer);
        $this->_em->flush($answer);
    }
}
