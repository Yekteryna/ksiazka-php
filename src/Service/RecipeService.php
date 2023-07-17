<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Utils\Paginator;
use App\Repository\RecipeRepository;
use MongoDB\Driver\Query;


class RecipeService
{
    public function __construct(
        protected RecipeRepository $recipeRepository,
        protected Paginator $paginator
    )
    {
    }

    public function queryAll(int $categoryId): \Doctrine\ORM\QueryBuilder
    {
        if ($categoryId) {
            return $this->recipeRepository->findByOrderDescAndCategory($categoryId);
        } else {
            return $this->recipeRepository->findByOrderDescAndCategory();
        }
    }
    public function getPagination(int $categoryId, int $page): Paginator
    {
        return $this->paginator->paginate($this->queryAll($categoryId), $page);
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