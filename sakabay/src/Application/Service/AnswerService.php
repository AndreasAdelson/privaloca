<?php

namespace App\Application\Service;

use App\Domain\Model\Answer;
use App\Infrastructure\Repository\AnswerRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;

class AnswerService
{
    /**
     * @var AnswerRepositoryInterface
     */
    private $answerRepository;

    /**
     * AnswerRestController constructor.
     */
    public function __construct(AnswerRepositoryInterface $answerRepository)
    {
        $this->answerRepository = $answerRepository;
    }

    /// Afficher un Answer
    public function getAnswer(int $answerId): ?Answer
    {
        return $this->answerRepository->find($answerId);
    }

    public function getAllAnswers(): ?array
    {
        return $this->answerRepository->findAll();
    }

    public function deleteAnswer(int $answerId): void
    {
        $answer = $this->answerRepository->find($answerId);
        if (!$answer) {
            throw new EntityNotFoundException('Answer with id ' . $answerId . ' does not exist!');
        }
        $this->answerRepository->delete($answer);
    }

    /**
     * Retourne une page, potentiellement triée et filtrée.
     *
     *
     * @param string $sortBy
     * @param bool   $descending
     * @param string $filterFields
     * @param string $filterText
     * @param int    $currentPage
     * @param int    $perPage
     *
     * @return Pagerfanta
     */
    public function getPaginatedList(
        $sortBy = 'id',
        $descending = false,
        $filterFields = '',
        $filterText = '',
        $currentPage = 1,
        $perPage = PHP_INT_MAX ? PHP_INT_MAX : 10
    ) {
        return $this->answerRepository
            ->getPaginatedList($sortBy, $descending, $filterFields, $filterText, $currentPage, $perPage);
    }
}
