<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * CommentController.
 */

#[Route('/comment')]
class CommentController extends AbstractController
{
    /**
     * Index action.
     *
     * @param CategoryRepository $categoryRepository CategoryRepository
     *
     * @return Response HTTP response
     */
    #[Route('/', name: 'app_comment_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * Delete action.
     *
     * @param Request $request Request
     * @param Comment $comment Comment
     * @param CommentRepository $commentRepository CommentRepository
     *
     * @return Response HTTP response
     */
    #[Route('/{id}', name: 'app_comment_delete', methods: ['post'])]
    public function delete(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $commentRepository->remove($comment, true);
        }

        return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
    }
}
