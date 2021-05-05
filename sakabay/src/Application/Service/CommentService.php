<?php

namespace App\Application\Service;

use App\Domain\Model\Comment;
use App\Infrastructure\Repository\CommentRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentService
{
    /**
     * @var CommentRepositoryInterface
     */
    private $commentRepository;

    /**
     * CommentRestController constructor.
     */
    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /// Afficher un Comment
    public function getComment(int $commentId): ?Comment
    {
        return $this->commentRepository->find($commentId);
    }

    public function getAllComments(): ?array
    {
        return $this->commentRepository->findAll();
    }

    public function deleteComment(int $commentId): void
    {
        $comment = $this->commentRepository->find($commentId);
        if (!$comment) {
            throw new EntityNotFoundException('Comment with id ' . $commentId . ' does not exist!');
        }
        $this->commentRepository->delete($comment);
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
        return $this->commentRepository
            ->getPaginatedList($sortBy, $descending, $filterFields, $filterText, $currentPage, $perPage);
    }
}
