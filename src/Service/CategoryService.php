<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;

class CategoryService
{
    public function __construct(
        protected CategoryRepository $categoryRepository
    ){ }

    public function findAll()
    {
        return $this->categoryRepository->findAll();
    }

    public function save(Category $category): void
    {
        $this->categoryRepository->save($category, true);
    }

    public function remove(Category $category): void
    {
        $this->categoryRepository->remove($category, true);
    }
}