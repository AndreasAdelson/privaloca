<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Comment;

/**
 * Interface CommentRepositoryInterface.
 */
interface CommentRepositoryInterface
{
    public function save(Comment $comment): void;

    public function delete(Comment $comment): void;
}
