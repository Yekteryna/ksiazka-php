<?php

namespace App\Service;

use App\Entity\Recipe;
use App\Utils\Paginator;
use App\Repository\RecipeRepository;


class RecipeService
{
    public function __construct(
        protected RecipeRepository $recipeRepository,
        protected Paginator $paginator
    )
    {
    }

    public function getPagination(\Doctrine\ORM\QueryBuilder $query, int $page): Paginator
    {
        return $this->paginator->paginate($query, $page);
    }

    public function findByOrderDescAndCategory(int $categoryId = null): \Doctrine\ORM\QueryBuilder
    {
        return $this->recipeRepository->findByOrderDescAndCategory($categoryId);
    }

    public function findAll()
    {
        return $this->recipeRepository->findAll();
    }

    public function save(Recipe $recipe): void
    {
        $this->recipeRepository->save($recipe, true);
    }

    public function remove(Recipe $recipe): void
    {
        $this->recipeRepository->remove($recipe, true);
    }
}