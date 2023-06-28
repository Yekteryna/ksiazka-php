<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function save(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

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

    public function filterByCategory(\Doctrine\ORM\QueryBuilder $builder, $categoryId): \Doctrine\ORM\QueryBuilder
    {
        return $builder->where('r.category_id = :category_id')
            ->setParameter('category_id', $categoryId);
    }
}
