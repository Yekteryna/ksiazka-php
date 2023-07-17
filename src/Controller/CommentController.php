<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Service\CategoryService;
use App\Service\CommentService;
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
    public function __construct(protected CategoryService $categoryService, protected CommentService $commentService) {}

    /**
     * Index action.
     *
     * @return Response HTTP response
     */
    #[Route('/', name: 'app_comment_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $this->categoryService->findAll(),
        ]);
    }

    /**
     * Delete action.
     *
     * @param Request $request Request
     * @param Comment $comment Comment
     *
     * @return Response HTTP response
     */
    #[Route('/{id}', name: 'app_comment_delete', methods: ['post'])]
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $this->commentService->remove($comment, true);
        }

        return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
    }
}
