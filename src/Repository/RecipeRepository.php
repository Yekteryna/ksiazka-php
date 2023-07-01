<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository class for Recipe entity.
 *
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    /**
     * RecipeRepository constructor.
     *
     * @param ManagerRegistry $registry The manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * Save a recipe entity.
     *
     * @param Recipe $entity The recipe entity to save
     * @param bool $flush Whether to flush the changes to the database (optional)
     */
    public function save(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Remove a recipe entity.
     *
     * @param Recipe $entity The recipe entity to remove
     * @param bool $flush Whether to flush the changes to the database (optional)
     */
    public function remove(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Get a query builder for filtering recipes by category and ordering by creation date in descending order.
     *
     * @param int|null $categoryId The ID of the category to filter by (optional)
     *
     * @return \Doctrine\ORM\QueryBuilder The query builder
     */
    public function findByOrderDescAndCategory(int $categoryId = null): \Doctrine\ORM\QueryBuilder
    {
        $query = $this->createQueryBuilder('r')->orderBy('r.created_at', 'desc');

        if ($categoryId) {
            $query->join('r.category', 'c')
                ->where('c.id = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }

        return $query;
    }

    /**
     * Filter the given query builder by category ID.
     *
     * @param \Doctrine\ORM\QueryBuilder $builder The query builder to filter
     * @param int|null $categoryId The ID of the category to filter by
     *
     * @return \Doctrine\ORM\QueryBuilder The filtered query builder
     */
    public function filterByCategory(\Doctrine\ORM\QueryBuilder $builder, $categoryId): \Doctrine\ORM\QueryBuilder
    {
        return $builder->andWhere('r.category_id = :category_id')
            ->setParameter('category_id', $categoryId);
    }
}