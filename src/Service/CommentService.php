<?php

namespace App\Service;

use App\Entity\Comment;
use App\Repository\CommentRepository;

class CommentService
{
    public function __construct(
        protected CommentRepository $commentRepository
    ){ }

    public function remove(Comment $comment): void
    {
        $this->commentRepository->remove($comment, true);
    }

    public function save(Comment $comment): void
    {
        $this->commentRepository->save($comment, true);
    }
}