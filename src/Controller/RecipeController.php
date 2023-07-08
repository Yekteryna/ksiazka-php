<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Form\CommentType;
use App\Form\Recipe2Type;
use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
use App\Service\RecipeService;
use App\Utils\Paginator;
use DateTimeImmutable;
use JetBrains\PhpStorm\NoReturn;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * RecipeController.
 */
#[Route('/recipe')]
class RecipeController extends AbstractController
{
    public function __construct(protected RecipeService $recipeService) {}

    /**
     * Index action.
     *
     * @param Request $request Request
     * @param RecipeRepository $recipeRepository RecipeRepository
     * @param Paginator $paginator Paginator
     *
     * @return Response HTTP response
     */
    #[Route('/', name: 'app_recipe_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        if ($categoryId = $request->query->get('category_id')) {
            $query = $this->recipeService->findByOrderDescAndCategory($categoryId);
        } else {
            $query = $this->recipeService->findByOrderDescAndCategory();
        }

        $paginator = $this->recipeService->getPagination($query, $request->query->getInt('page', 1));

        return $this->render('recipe/index.html.twig', [
            'recipes' => $this->recipeService->findAll(),
            'paginator' => $paginator,
        ]);
    }

    /**
     * New action.
     *
     * @param Request $request Request
     * @param RecipeRepository $recipeRepository RecipeRepository
     *
     * @return Response HTTP response
     */
    #[Route('/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(Recipe2Type::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe->setUserId($this->getUser());
            $recipe->setCreatedAt((new DateTimeImmutable));
            $this->recipeService->save($recipe, true);

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    /**
     * Show action.
     *
     * @param Recipe $recipe Recipe
     *
     * @return Response HTTP response
     */
    #[Route('/{id}', name: 'app_recipe_show', methods: ['GET'])]
    public function show(Recipe $recipe): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
            'comments' => $recipe->getComments(),
            'form' => $form,
        ]);
    }

    /**
     * Edit action.
     *
     * @param Request $request Request
     * @param Recipe $recipe Recipe
     * @param RecipeRepository $recipeRepository RecipeRepository
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Recipe $recipe): Response
    {
        $user = $this->getUser();

        if ($user !== $recipe->getUser()){
            return $this->redirect('/access-denied');
        }

        $form = $this->createForm(Recipe2Type::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipeService->save($recipe, true);

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    /**
     * Delete action.
     *
     * @param Request $request Request
     * @param Recipe $recipe Recipe
     * @param RecipeRepository $recipeRepository RecipeRepository
     *
     * @return Response HTTP response
     */
    #[Route('/{id}', name: 'app_recipe_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Recipe $recipe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            $this->recipeService->remove($recipe, true);
        }

        return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * NewComment action.
     *
     * @param Request $request Request
     * @param Recipe $recipe Recipe
     * @param CommentRepository $commentRepository CommentRepository
     *
     * @return Response HTTP response
     */
    #[NoReturn] #[Route('{id}/comment/new', name: 'app_comment_new', methods: ['POST'])]
    public function newComment(Request $request, Recipe $recipe, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $comment->setRecipe($recipe);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $comment->setCreatedAt((new DateTimeImmutable));
            $commentRepository->save($comment, true);

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->redirect('/recipe');
    }
}
