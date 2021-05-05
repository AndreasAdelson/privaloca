<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class CommentRepository extends AbstractRepository implements CommentRepositoryInterface
{

    /**
     * CommentRepository constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(Comment::class));
    }

    public function save(Comment $comment): void
    {
        $this->_em->persist($comment);
        $this->_em->flush($comment);
    }

    public function delete(Comment $comment): void
    {
        $this->_em->remove($comment);
        $this->_em->flush($comment);
    }
}
